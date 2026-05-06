<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Batch;
use App\Models\SalesOrder;
use App\Models\SalesOrderItem;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockOutController extends Controller
{
    public function index()
    {
        $salesOrders = SalesOrder::with(['customer', 'creator'])
            ->where('company_id', auth()->user()->company_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        return view('stock-out.index', compact('salesOrders'));
    }

    public function create()
    {
        $customers = Customer::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
        $products = Product::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
        $warehouses = Warehouse::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
        return view('stock-out.create', compact('customers', 'products', 'warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'delivery_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $so = SalesOrder::create([
                'so_number' => SalesOrder::generateNumber(),
                'company_id' => auth()->user()->company_id,
                'customer_id' => $request->customer_id,
                'warehouse_id' => $request->warehouse_id,
                'created_by' => auth()->id(),
                'order_date' => now(),
                'delivery_date' => $request->delivery_date,
                'status' => 'delivered',
                'notes' => $request->notes,
                'total_amount' => 0,
            ]);

            $totalAmount = 0;
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                // Ambil batch dengan stok > 0, urutkan expiry (FEFO)
                $batches = Batch::where('product_id', $item['product_id'])
                    ->where('warehouse_id', $request->warehouse_id)
                    ->where('quantity', '>', 0)
                    ->orderBy('expiry_date', 'asc')
                    ->get();

                $remainingQty = $item['quantity'];
                $unitPrice = $product->selling_price;

                foreach ($batches as $batch) {
                    if ($remainingQty <= 0) break;
                    $taken = min($batch->quantity, $remainingQty);
                    $totalPrice = $taken * $unitPrice;
                    $totalAmount += $totalPrice;

                    // Kurangi stok batch
                    $batch->quantity -= $taken;
                    $batch->save();

                    // Catat stock movement
                    StockMovement::create([
                        'company_id' => auth()->user()->company_id,
                        'warehouse_id' => $request->warehouse_id,
                        'product_id' => $item['product_id'],
                        'batch_id' => $batch->id,
                        'type' => 'out',
                        'category' => 'sales',
                        'reference_number' => $so->so_number,
                        'reference_type' => 'sales_order',
                        'reference_id' => $so->id,
                        'quantity' => $taken,
                        'stock_before' => $batch->quantity + $taken,
                        'stock_after' => $batch->quantity,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                        'created_by' => auth()->id(),
                        'movement_date' => $request->delivery_date,
                    ]);

                    // Simpan item
                    SalesOrderItem::create([
                        'sales_order_id' => $so->id,
                        'product_id' => $item['product_id'],
                        'batch_id' => $batch->id,
                        'quantity' => $taken,
                        'unit_price' => $unitPrice,
                        'total_price' => $totalPrice,
                    ]);

                    $remainingQty -= $taken;
                }

                if ($remainingQty > 0) {
                    throw new \Exception("Stok tidak mencukupi untuk produk {$product->name}. Sisa dibutuhkan: {$remainingQty}");
                }
            }

            $so->update(['total_amount' => $totalAmount]);
            DB::commit();
            
            // Log activity
            ActivityLogger::log('create', 'stock_out', $so->id, null, $so->toArray(), 'Stock Out: SO ' . $so->so_number);
            
            return redirect()->route('stock-out.index')->with('success', "Stock Out successful! SO: {$so->so_number}");
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(SalesOrder $salesOrder)
    {
        $salesOrder->load(['customer', 'items.product', 'items.batch', 'creator']);
        return view('stock-out.show', compact('salesOrder'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Batch;
use App\Models\Supplier;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\GoodsReceipt;
use App\Models\GoodsReceiptItem;
use App\Models\StockMovement;
use App\Models\Warehouse;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockInController extends Controller
{
    public function index()
    {
        $goodsReceipts = GoodsReceipt::with(['purchaseOrder.supplier', 'receiver'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('stock-in.index', compact('goodsReceipts'));
    }

    public function create()
    {
        $suppliers = Supplier::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
        
        $products = Product::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
        
        $warehouses = Warehouse::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
        
        return view('stock-in.create', compact('suppliers', 'products', 'warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'receipt_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.purchase_unit' => 'required|string',
            'items.*.conversion_factor' => 'required|numeric|min:0.001',
        ]);

        DB::beginTransaction();
        
        try {
            // Create Purchase Order
            $po = PurchaseOrder::create([
                'po_number' => $this->generatePONumber(),
                'company_id' => auth()->user()->company_id,
                'supplier_id' => $request->supplier_id,
                'warehouse_id' => $request->warehouse_id,
                'created_by' => auth()->id(),
                'order_date' => now(),
                'expected_date' => $request->receipt_date,
                'received_date' => $request->receipt_date,
                'status' => 'completed',
                'total_amount' => 0,
                'notes' => $request->notes,
            ]);
            
            $totalAmount = 0;
            $grItems = [];
            
            // Create Purchase Order Items and Goods Receipt Items
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                
                // ============================================
                // UNIT CONVERSION LOGIC
                // ============================================
                $purchaseUnit = $item['purchase_unit'];
                $conversionFactor = $item['conversion_factor'];
                $quantity = $item['quantity'];
                $unitPrice = $item['unit_price'];
                
                // Convert to base unit
                $baseQuantity = $quantity * $conversionFactor;
                $baseUnitPrice = $unitPrice / $conversionFactor;
                $totalPrice = $baseQuantity * $baseUnitPrice;
                
                $totalAmount += $totalPrice;
                
                $poItem = PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $quantity,
                    'quantity_received' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);
                
                // ============================================
                // AUTO GENERATE BATCH NUMBER JIKA KOSONG
                // ============================================
                $batchNumber = $item['batch_number'] ?? '';
                if (empty($batchNumber)) {
                    $batchNumber = Batch::generateBatchNumber();
                }
                
                $batch = Batch::updateOrCreate(
                    [
                        'product_id' => $item['product_id'],
                        'warehouse_id' => $request->warehouse_id,
                        'batch_number' => $batchNumber,
                    ],
                    [
                        'quantity' => DB::raw('quantity + ' . $baseQuantity),
                        'initial_quantity' => DB::raw('initial_quantity + ' . $baseQuantity),
                        'purchase_price' => $baseUnitPrice,
                        'expiry_date' => $item['expiry_date'] ?? null,
                    ]
                );
                
                // Refresh batch quantity
                $batch->refresh();
                
                // Calculate stock before and after (in base unit)
                $stockBefore = $product->batches->sum('quantity') - $baseQuantity;
                $stockAfter = $product->batches->sum('quantity');
                
                // Create Stock Movement (in base unit)
                StockMovement::create([
                    'company_id' => auth()->user()->company_id,
                    'warehouse_id' => $request->warehouse_id,
                    'product_id' => $item['product_id'],
                    'batch_id' => $batch->id,
                    'type' => 'in',
                    'category' => 'purchase',
                    'reference_number' => $po->po_number,
                    'reference_type' => 'purchase_order',
                    'reference_id' => $po->id,
                    'quantity' => $baseQuantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $stockAfter,
                    'unit_price' => $baseUnitPrice,
                    'total_price' => $totalPrice,
                    'created_by' => auth()->id(),
                    'movement_date' => $request->receipt_date,
                ]);
                
                $grItems[] = [
                    'purchase_order_item_id' => $poItem->id,
                    'product_id' => $item['product_id'],
                    'batch_id' => $batch->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'expiry_date' => $item['expiry_date'] ?? null,
                    'purchase_unit' => $purchaseUnit,
                    'conversion_factor' => $conversionFactor,
                    'base_quantity' => $baseQuantity,
                    'base_unit_price' => $baseUnitPrice,
                ];
            }
            
            // Update PO Total Amount
            $po->update(['total_amount' => $totalAmount]);
            
            // Create Goods Receipt
            $gr = GoodsReceipt::create([
                'gr_number' => $this->generateGRNumber(),
                'purchase_order_id' => $po->id,
                'warehouse_id' => $request->warehouse_id,
                'received_by' => auth()->id(),
                'receipt_date' => $request->receipt_date,
                'notes' => $request->notes,
            ]);
            
            // Create Goods Receipt Items
            foreach ($grItems as $item) {
                GoodsReceiptItem::create(array_merge($item, ['goods_receipt_id' => $gr->id]));
            }
            
            DB::commit();
            
            // Log activity
            ActivityLogger::log('create', 'stock_in', $gr->id, null, $gr->toArray(), 'Stock In: PO ' . $po->po_number . ' | GR ' . $gr->gr_number);
            
            return redirect()->route('stock-in.index')
                ->with('success', 'Stock In successful! PO: ' . $po->po_number . ' | GR: ' . $gr->gr_number);
                
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function show(GoodsReceipt $goodsReceipt)
    {
        $goodsReceipt->load(['purchaseOrder.supplier', 'items.product', 'items.batch', 'receiver']);
        return view('stock-in.show', compact('goodsReceipt'));
    }
    
    private function generatePONumber()
    {
        $year = date('Y');
        $month = date('m');
        $last = PurchaseOrder::where('po_number', 'LIKE', "PO/{$year}/{$month}/%")
            ->orderBy('id', 'desc')
            ->first();
        
        if ($last) {
            $lastNumber = (int) substr($last->po_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return "PO/{$year}/{$month}/{$newNumber}";
    }
    
    private function generateGRNumber()
    {
        $year = date('Y');
        $month = date('m');
        $last = GoodsReceipt::where('gr_number', 'LIKE', "GR/{$year}/{$month}/%")
            ->orderBy('id', 'desc')
            ->first();
        
        if ($last) {
            $lastNumber = (int) substr($last->gr_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return "GR/{$year}/{$month}/{$newNumber}";
    }
}
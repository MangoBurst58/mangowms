<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class BatchController extends Controller
{
    public function index()
    {
        $batches = Batch::with(['product', 'warehouse'])
            ->whereHas('warehouse', function($q) {
                $q->where('company_id', auth()->user()->company_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('batches.index', compact('batches'));
    }

    public function create()
    {
        $products = Product::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
            
        $warehouses = Warehouse::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
        
        return view('batches.create', compact('products', 'warehouses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'batch_number' => 'required|string|unique:batches,batch_number',
            'quantity' => 'required|integer|min:1',
            'purchase_price' => 'required|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'manufacture_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        
        Batch::create([
            'product_id' => $request->product_id,
            'warehouse_id' => $request->warehouse_id,
            'batch_number' => $request->batch_number,
            'manufacture_date' => $request->manufacture_date,
            'expiry_date' => $request->expiry_date,
            'quantity' => $request->quantity,
            'initial_quantity' => $request->quantity,
            'purchase_price' => $request->purchase_price,
            'notes' => $request->notes,
        ]);
        
        return redirect()->route('batches.index')->with('success', 'Batch created successfully!');
    }

    public function show(Batch $batch)
    {
        return view('batches.show', compact('batch'));
    }

    public function edit(Batch $batch)
    {
        $products = Product::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
            
        $warehouses = Warehouse::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
        
        return view('batches.edit', compact('batch', 'products', 'warehouses'));
    }

    public function update(Request $request, Batch $batch)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'batch_number' => 'required|string|unique:batches,batch_number,' . $batch->id,
            'quantity' => 'required|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'expiry_date' => 'nullable|date',
            'manufacture_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);
        
        $batch->update([
            'product_id' => $request->product_id,
            'warehouse_id' => $request->warehouse_id,
            'batch_number' => $request->batch_number,
            'manufacture_date' => $request->manufacture_date,
            'expiry_date' => $request->expiry_date,
            'quantity' => $request->quantity,
            'purchase_price' => $request->purchase_price,
            'notes' => $request->notes,
        ]);
        
        return redirect()->route('batches.index')->with('success', 'Batch updated successfully!');
    }

    public function destroy(Batch $batch)
    {
        $batch->delete();
        
        return redirect()->route('batches.index')->with('success', 'Batch deleted successfully!');
    }
}
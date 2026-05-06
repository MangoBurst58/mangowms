<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::where('company_id', auth()->user()->company_id)
            ->orderBy('name')
            ->paginate(10);
        
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|unique:suppliers',  // Code boleh kosong
            'name' => 'required',
            'email' => 'nullable|email',
        ]);

        // Auto generate code jika kosong
        $code = $request->code;
        if (empty($code)) {
            $code = Supplier::generateCode();
        }

        $supplier = Supplier::create([
            'company_id' => auth()->user()->company_id,
            'code' => $code,
            'name' => $request->name,
            'tax_id' => $request->tax_id,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'contact_person' => $request->contact_person,
            'contact_phone' => $request->contact_phone,
            'is_active' => $request->has('is_active'),
        ]);

        // Log activity
        ActivityLogger::log('create', 'supplier', $supplier->id, null, $supplier->toArray(), 'Created supplier: ' . $supplier->name);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully! Code: ' . $code);
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'code' => 'required|unique:suppliers,code,' . $supplier->id,
            'name' => 'required',
            'email' => 'nullable|email',
        ]);

        $oldData = $supplier->toArray();

        $supplier->update([
            'code' => $request->code,
            'name' => $request->name,
            'tax_id' => $request->tax_id,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'contact_person' => $request->contact_person,
            'contact_phone' => $request->contact_phone,
            'is_active' => $request->has('is_active'),
        ]);

        // Log activity
        ActivityLogger::log('update', 'supplier', $supplier->id, $oldData, $supplier->toArray(), 'Updated supplier: ' . $supplier->name);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplierName = $supplier->name;
        $supplierId = $supplier->id;
        $oldData = $supplier->toArray();

        $supplier->delete();

        // Log activity
        ActivityLogger::log('delete', 'supplier', $supplierId, $oldData, null, 'Deleted supplier: ' . $supplierName);

        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}
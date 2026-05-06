<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Helpers\ActivityLogger;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::where('company_id', auth()->user()->company_id)
            ->orderBy('name')
            ->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|unique:customers',  // Code boleh kosong
            'name' => 'required',
            'email' => 'nullable|email',
        ]);

        // Auto generate code jika kosong
        $code = $request->code;
        if (empty($code)) {
            $code = Customer::generateCode();
        }

        $customer = Customer::create([
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
        ActivityLogger::log('create', 'customer', $customer->id, null, $customer->toArray(), 'Created customer: ' . $customer->name);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully! Code: ' . $code);
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'code' => 'required|unique:customers,code,' . $customer->id,
            'name' => 'required',
            'email' => 'nullable|email',
        ]);

        $oldData = $customer->toArray();

        $customer->update([
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
        ActivityLogger::log('update', 'customer', $customer->id, $oldData, $customer->toArray(), 'Updated customer: ' . $customer->name);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $customerName = $customer->name;
        $customerId = $customer->id;
        $oldData = $customer->toArray();

        $customer->delete();

        // Log activity
        ActivityLogger::log('delete', 'customer', $customerId, $oldData, null, 'Deleted customer: ' . $customerName);

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\Company;
use App\Models\Warehouse;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles', 'company', 'warehouse')->get();
        return view('users.index', compact('users'));
    }
    
    public function create()
    {
        $roles = Role::all();
        $companies = Company::all();
        $warehouses = Warehouse::all();
        return view('users.create', compact('roles', 'companies', 'warehouses'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required|exists:roles,name',
            'company_id' => 'nullable|exists:companies,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'position' => 'nullable',
            'phone' => 'nullable',
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('password123'),
            'company_id' => $request->company_id,
            'warehouse_id' => $request->warehouse_id,
            'position' => $request->position,
            'phone' => $request->phone,
            'is_active' => true,
        ]);
        
        $user->assignRole($request->role);
        
        return redirect()->route('users.index')->with('success', 'User created successfully. Default password: password123');
    }
    
    public function edit(User $user)
    {
        $roles = Role::all();
        $companies = Company::all();
        $warehouses = Warehouse::all();
        return view('users.edit', compact('user', 'roles', 'companies', 'warehouses'));
    }
    
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|exists:roles,name',
            'company_id' => 'nullable|exists:companies,id',
            'warehouse_id' => 'nullable|exists:warehouses,id',
            'position' => 'nullable',
            'phone' => 'nullable',
            'is_active' => 'boolean',
        ]);
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'company_id' => $request->company_id,
            'warehouse_id' => $request->warehouse_id,
            'position' => $request->position,
            'phone' => $request->phone,
            'is_active' => $request->has('is_active'),
        ]);
        
        $user->syncRoles([$request->role]);
        
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }
    
    public function destroy(User $user)
    {
        if ($user->hasRole('super_admin') && User::role('super_admin')->count() <= 1) {
            return back()->with('error', 'Cannot delete the last Super Admin.');
        }
        
        $user->delete();
        
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
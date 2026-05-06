@extends('layouts.app')

@section('title', 'Suppliers')

@section('content')
<div class="space-y-6">
    
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">
            <i class="fas fa-truck mr-2 text-indigo-400"></i>
            Suppliers
        </h1>
        <a href="{{ route('suppliers.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-plus mr-2"></i> Add Supplier
        </a>
    </div>
    
    @if(session('success'))
        <div class="bg-emerald-900/50 border border-emerald-700 text-emerald-300 px-4 py-3 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-700">
                <thead>
                    <tr class="bg-slate-900/50">
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Code</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Name</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Email</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Phone</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Contact Person</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                    <tr class="hover:bg-slate-700/50 transition">
                        <td class="px-6 py-4 text-white">{{ $supplier->code }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $supplier->name }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $supplier->email ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $supplier->phone ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $supplier->contact_person ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($supplier->is_active)
                                <span class="px-2 py-1 text-xs rounded-full bg-emerald-900/50 text-emerald-300">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-900/50 text-red-300">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('suppliers.edit', $supplier) }}" class="text-emerald-400 hover:text-emerald-300 mr-3">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-truck text-4xl mb-3 block"></i>
                            No suppliers found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-700">
            {{ $suppliers->links() }}
        </div>
    </div>
    
</div>
@endsection
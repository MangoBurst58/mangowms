@extends('layouts.app')

@section('title', 'Stock In')

@section('content')
<div class="space-y-6">
    
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">
            <i class="fas fa-arrow-down mr-2 text-emerald-400"></i>
            Stock In / Goods Receipt
        </h1>
        <a href="{{ route('stock-in.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-plus mr-2"></i> New Stock In
        </a>
    </div>
    
    @if(session('success'))
        <div class="bg-emerald-900/50 border border-emerald-700 text-emerald-300 px-4 py-3 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 rounded-lg">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif
    
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-700">
                <thead>
                    <tr class="bg-slate-900/50">
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">GR Number</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">PO Number</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Supplier</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Warehouse</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Receipt Date</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($goodsReceipts as $gr)
                    <tr class="hover:bg-slate-700/50 transition">
                        <td class="px-6 py-4 text-white font-mono">{{ $gr->gr_number }}</td>
                        <td class="px-6 py-4 text-gray-300 font-mono">{{ $gr->purchaseOrder->po_number }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $gr->purchaseOrder->supplier->name }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $gr->warehouse->name }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $gr->receipt_date->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full bg-emerald-900/50 text-emerald-300">Completed</span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('stock-in.show', $gr) }}" class="text-indigo-400 hover:text-indigo-300">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-box-open text-4xl mb-3 block"></i>
                            No stock in records found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-700">
            {{ $goodsReceipts->links() }}
        </div>
    </div>
    
</div>
@endsection
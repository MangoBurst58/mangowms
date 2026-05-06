@extends('layouts.app')

@section('title', 'Movement Report')

@section('content')
<div class="space-y-6">
    
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">
            <i class="fas fa-chart-bar mr-2 text-indigo-400"></i>
            Movement Report
        </h1>
        <a href="{{ route('movement-report.export', request()->query()) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-file-excel mr-2"></i> Export to Excel
        </a>
    </div>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-emerald-900/30 rounded-lg">
                    <i class="fas fa-arrow-down text-emerald-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Stock In</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($totalIn) }} units</p>
                </div>
            </div>
        </div>
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-red-900/30 rounded-lg">
                    <i class="fas fa-arrow-up text-red-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Stock Out</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($totalOut) }} units</p>
                </div>
            </div>
        </div>
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-900/30 rounded-lg">
                    <i class="fas fa-money-bill-wave text-blue-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Transaction Value</p>
                    <p class="text-2xl font-bold text-white">Rp {{ number_format($totalValue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Filter Form -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-4">
        <form method="GET" action="{{ route('movement-report.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-1">Type</label>
                <select name="type" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white">
                    <option value="">All Types</option>
                    <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>Stock In (IN)</option>
                    <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Stock Out (OUT)</option>
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Product</label>
                <select name="product_id" class="product-filter w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white" style="width: 100%">
                    <option value="">All Products</option>
                    @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                        {{ $product->name }} (Stok: {{ $product->total_stock }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-1">Date To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white">
            </div>
            <div class="md:col-span-4 flex justify-end gap-3">
                <a href="{{ route('movement-report.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition">
                    Reset
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>
    
    <!-- Movements Table -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-700">
                <thead>
                    <tr class="bg-slate-900/50">
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Reference</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Product</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Batch</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Warehouse</th>
                        <th class="px-6 py-3 text-right text-xs text-gray-400 uppercase">Quantity</th>
                        <th class="px-6 py-3 text-right text-xs text-gray-400 uppercase">Unit Price</th>
                        <th class="px-6 py-3 text-right text-xs text-gray-400 uppercase">Total</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Created By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($movements as $movement)
                    <tr class="hover:bg-slate-700/50 transition border-b border-slate-700">
                        <td class="px-6 py-4 whitespace-nowrap text-white">
                            {{ \Carbon\Carbon::parse($movement->movement_date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($movement->type == 'in')
                                <span class="px-2 py-1 text-xs rounded-full bg-emerald-900/50 text-emerald-300">
                                    <i class="fas fa-arrow-down mr-1"></i> IN
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-red-900/50 text-red-300">
                                    <i class="fas fa-arrow-up mr-1"></i> OUT
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-300 font-mono text-sm">
                            {{ $movement->reference_number ?? '-' }}
                        <td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                            {{ $movement->product->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-400 text-sm">
                            {{ $movement->batch->batch_number ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                            {{ $movement->warehouse->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-white">
                            {{ number_format($movement->quantity) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-white">
                            Rp {{ number_format($movement->unit_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-white font-semibold">
                            Rp {{ number_format($movement->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-400 text-sm">
                            {{ $movement->creator->name ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-chart-bar text-4xl mb-3 block"></i>
                            No movement records found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-700">
            {{ $movements->appends(request()->query())->links() }}
        </div>
    </div>
    
</div>

<script>
$(document).ready(function() {
    $('.product-filter').select2({
        placeholder: 'Search product...',
        allowClear: true,
        width: '100%'
    });
});
</script>
@endsection
@extends('layouts.app')

@section('title', 'Stock Movement | MangoWMS')
@section('title', 'Stock Movement History')

@section('content')
<div class="space-y-6">
    
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">
            <i class="fas fa-history mr-2 text-indigo-400"></i>
            Stock Movement History
        </h1>
    </div>
    
    <!-- Filter Form -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-4">
        <form method="GET" action="{{ route('stock-movement.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
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
                <a href="{{ route('stock-movement.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition">
                    Reset
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
            </div>
        </form>
    </div>
    
    <!-- Movements Table - RAPI -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-slate-700" style="min-width: 1100px; table-layout: fixed">
                <thead>
                    <tr class="bg-slate-900/50">
                        <th class="px-4 py-3 text-left text-xs text-gray-400 uppercase" style="width: 90px">Date</th>
                        <th class="px-4 py-3 text-left text-xs text-gray-400 uppercase" style="width: 70px">Type</th>
                        <th class="px-4 py-3 text-left text-xs text-gray-400 uppercase" style="width: 120px">Reference</th>
                        <th class="px-4 py-3 text-left text-xs text-gray-400 uppercase" style="width: 180px">Product</th>
                        <th class="px-4 py-3 text-left text-xs text-gray-400 uppercase" style="width: 100px">Batch</th>
                        <th class="px-4 py-3 text-right text-xs text-gray-400 uppercase" style="width: 70px">Qty</th>
                        <th class="px-4 py-3 text-right text-xs text-gray-400 uppercase" style="width: 110px">Unit Price</th>
                        <th class="px-4 py-3 text-right text-xs text-gray-400 uppercase" style="width: 130px">Total</th>
                        <th class="px-4 py-3 text-left text-xs text-gray-400 uppercase" style="width: 110px">Created By</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($movements as $movement)
                    <tr class="hover:bg-slate-700/50 transition">
                        <td class="px-4 py-3 text-white text-sm">
                            {{ \Carbon\Carbon::parse($movement->movement_date)->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3">
                            @if($movement->type == 'in')
                                <span class="px-2 py-0.5 text-xs rounded-full bg-emerald-900/50 text-emerald-300">
                                    IN
                                </span>
                            @else
                                <span class="px-2 py-0.5 text-xs rounded-full bg-red-900/50 text-red-300">
                                    OUT
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-300 font-mono text-xs">
                            {{ $movement->reference_number ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-gray-300 text-sm truncate" title="{{ $movement->product->name }}">
                            {{ Str::limit($movement->product->name, 30) }}
                        </td>
                        <td class="px-4 py-3 text-gray-400 text-xs">
                            {{ $movement->batch->batch_number ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-right text-white text-sm">
                            {{ number_format($movement->quantity) }}
                        </td>
                        <td class="px-4 py-3 text-right text-white text-sm">
                            Rp {{ number_format($movement->unit_price, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right text-white font-semibold text-sm">
                            Rp {{ number_format($movement->total_price, 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-gray-400 text-xs">
                            {{ $movement->creator->name ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-12 text-center text-gray-400">
                            <i class="fas fa-history text-4xl mb-3 block"></i>
                            No stock movements found
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
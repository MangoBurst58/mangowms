@extends('layouts.app')

@section('title', 'Stock Report')

@section('content')
<div class="space-y-6">
    
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">
            <i class="fas fa-chart-line mr-2 text-indigo-400"></i>
            Stock Report
        </h1>
        <a href="{{ route('stock-report.export') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-file-excel mr-2"></i> Export to Excel
        </a>
    </div>
    
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-indigo-900/30 rounded-lg">
                    <i class="fas fa-box text-indigo-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Products</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($totalProducts) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-emerald-900/30 rounded-lg">
                    <i class="fas fa-cubes text-emerald-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Stock (Units)</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($totalStock) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-900/30 rounded-lg">
                    <i class="fas fa-money-bill-wave text-blue-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Total Stock Value</p>
                    <p class="text-2xl font-bold text-white">Rp {{ number_format($totalValue, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        <div class="bg-slate-800 rounded-xl border border-slate-700 p-5">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-amber-900/30 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-amber-400 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Low Stock Items</p>
                    <p class="text-2xl font-bold text-amber-400">{{ number_format($lowStockCount) }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stock Table -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-700">
                <thead>
                    <tr class="bg-slate-900/50">
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">SKU</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Product Name</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Category</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Unit</th>
                        <th class="px-6 py-3 text-right text-xs text-gray-400 uppercase">Current Stock</th>
                        <th class="px-6 py-3 text-right text-xs text-gray-400 uppercase">Min Stock</th>
                        <th class="px-6 py-3 text-right text-xs text-gray-400 uppercase">Max Stock</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Status</th>
                        <th class="px-6 py-3 text-right text-xs text-gray-400 uppercase">Purchase Price</th>
                        <th class="px-6 py-3 text-right text-xs text-gray-400 uppercase">Selling Price</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="hover:bg-slate-700/50 transition border-b border-slate-700">
                        <td class="px-6 py-4 text-white">{{ $product->sku }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $product->name }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $product->category->name ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $product->unit }}</td>
                        <td class="px-6 py-4 text-right text-white font-semibold">
                            {{ number_format($product->current_stock) }}
                        </td>
                        <td class="px-6 py-4 text-right text-gray-300">
                            {{ number_format($product->min_stock) }}
                        </td>
                        <td class="px-6 py-4 text-right text-gray-300">
                            {{ $product->max_stock ? number_format($product->max_stock) : '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($product->current_stock <= 0)
                                <span class="px-2 py-1 text-xs rounded-full bg-red-900/50 text-red-300">Out of Stock</span>
                            @elseif($product->current_stock <= $product->min_stock)
                                <span class="px-2 py-1 text-xs rounded-full bg-amber-900/50 text-amber-300">Low Stock</span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-emerald-900/50 text-emerald-300">Normal</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right text-gray-300">
                            Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right text-gray-300">
                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-box-open text-4xl mb-3 block"></i>
                            No products found
                        </td>
                    </table>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
</div>
@endsection
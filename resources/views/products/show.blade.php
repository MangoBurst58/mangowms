@extends('layouts.app')

@section('title', 'Product Details')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-white">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-white">
            <i class="fas fa-info-circle mr-2 text-indigo-400"></i>
            Product Details: {{ $product->name }}
        </h1>
    </div>
    
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
    <div class="p-6 border-b md:border-b-0 md:border-r border-slate-700">
        <h2 class="text-lg font-semibold text-white mb-4">Basic Information</h2>
        <div class="space-y-3">
            <div>
                <p class="text-sm text-gray-400">SKU</p>
                <p class="text-white font-mono">{{ $product->sku }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-400">Product Name</p>
                <p class="text-white">{{ $product->name }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-400">Barcode</p>
                @if($product->barcode)
                    <p class="text-white font-mono">{{ $product->barcode }}</p>
                @else
                    <p class="text-gray-500 italic">Not set</p>
                @endif
            </div>
            <div>
                <p class="text-sm text-gray-400">Category</p>
                <p class="text-white">{{ $product->category->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-400">Unit</p>
                <p class="text-white">{{ $product->unit }}</p>
            </div>
        </div>
    </div>
    
    <div class="p-6">
        <h2 class="text-lg font-semibold text-white mb-4">Price & Stock</h2>
        <div class="space-y-3">
            <div>
                <p class="text-sm text-gray-400">Purchase Price</p>
                <p class="text-white">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-400">Selling Price</p>
                <p class="text-white">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-400">Current Stock</p>
                <p class="text-white text-2xl font-bold">{{ number_format($product->total_stock) }} {{ $product->unit }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-400">Stock Limits</p>
                <p class="text-white">Min: {{ number_format($product->min_stock) }} / Max: {{ $product->max_stock ? number_format($product->max_stock) : '∞' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-400">Status</p>
                @if($product->is_active)
                    <span class="px-2 py-1 text-xs rounded-full bg-emerald-900/50 text-emerald-300">Active</span>
                @else
                    <span class="px-2 py-1 text-xs rounded-full bg-red-900/50 text-red-300">Inactive</span>
                @endif
            </div>
        </div>
    </div>
</div>
        
        <div class="border-t border-slate-700 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Description</h2>
            <p class="text-gray-300">{{ $product->description ?? '-' }}</p>
        </div>
    </div>
    
    <div class="flex justify-end gap-3 mt-6">
        <a href="{{ route('products.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
        <a href="{{ route('products.edit', $product) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
            <i class="fas fa-edit mr-2"></i> Edit Product
        </a>
    </div>
    
</div>
@endsection
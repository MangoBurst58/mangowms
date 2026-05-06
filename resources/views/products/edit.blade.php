@extends('layouts.app')

@section('title', 'Edit Product | MangoWMS')
@section('title', 'Edit Product')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('products.index') }}" class="text-gray-400 hover:text-white">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1 class="text-2xl font-bold text-white">
            <i class="fas fa-edit mr-2 text-indigo-400"></i>
            Edit Product: {{ $product->name }}
        </h1>
    </div>
    
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- SKU Field (Readonly) -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        SKU <span class="text-gray-500">(Unique Identifier)</span>
                    </label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" 
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-gray-400 focus:border-indigo-500 focus:outline-none cursor-not-allowed"
                           readonly>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-lock mr-1"></i> SKU cannot be changed
                    </p>
                </div>
                
                <!-- Barcode Field -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Barcode <span class="text-gray-500">(Optional)</span>
                    </label>
                    <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}" 
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none"
                           placeholder="Scan or enter barcode">
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-qrcode mr-1"></i> For barcode scanning
                    </p>
                    @error('barcode') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Product Name -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Product Name *</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" 
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none"
                           required>
                </div>
                
                <!-- Category -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>
                    <select name="category_id" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Unit -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Unit *</label>
                    <select name="unit" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none" required>
                        <option value="pcs" {{ old('unit', $product->unit) == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                        <option value="box" {{ old('unit', $product->unit) == 'box' ? 'selected' : '' }}>Box</option>
                        <option value="carton" {{ old('unit', $product->unit) == 'carton' ? 'selected' : '' }}>Carton</option>
                        <option value="kg" {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                        <option value="liter" {{ old('unit', $product->unit) == 'liter' ? 'selected' : '' }}>Liter</option>
                    </select>
                </div>
                
                <!-- Base Unit (New!) -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        Base Unit <span class="text-gray-500">(Stock & Price Unit)</span>
                    </label>
                    <select name="base_unit" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none">
                        <option value="pcs" {{ old('base_unit', $product->base_unit ?? 'pcs') == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                        <option value="kg" {{ old('base_unit', $product->base_unit ?? 'pcs') == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                        <option value="liter" {{ old('base_unit', $product->base_unit ?? 'pcs') == 'liter' ? 'selected' : '' }}>Liter (L)</option>
                        <option value="box" {{ old('base_unit', $product->base_unit ?? 'pcs') == 'box' ? 'selected' : '' }}>Box</option>
                        <option value="carton" {{ old('base_unit', $product->base_unit ?? 'pcs') == 'carton' ? 'selected' : '' }}>Carton</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        <i class="fas fa-database mr-1"></i> Base unit for stock and price calculation
                    </p>
                    @error('base_unit') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                    <textarea name="description" rows="3" 
                              class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none">{{ old('description', $product->description) }}</textarea>
                </div>
                
                <!-- Purchase Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Purchase Price *</label>
                    <input type="number" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" step="0.01"
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none"
                           required min="0">
                </div>
                
                <!-- Selling Price -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Selling Price *</label>
                    <input type="number" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" step="0.01"
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none"
                           required min="0">
                </div>
                
                <!-- Minimum Stock -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Minimum Stock *</label>
                    <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}"
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none"
                           required min="0">
                </div>
                
                <!-- Maximum Stock -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Maximum Stock</label>
                    <input type="number" name="max_stock" value="{{ old('max_stock', $product->max_stock) }}"
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none"
                           min="0">
                </div>
                
                <!-- Reorder Point -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Reorder Point</label>
                    <input type="number" name="reorder_point" value="{{ old('reorder_point', $product->reorder_point) }}"
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none"
                           min="0">
                </div>
                
                <!-- Active -->
                <div class="flex items-center gap-3">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-indigo-600 border-slate-700 rounded focus:ring-indigo-500">
                    <label class="text-sm font-medium text-gray-300">Active</label>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-700">
                <a href="{{ route('products.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                    <i class="fas fa-save mr-2"></i> Update Product
                </button>
            </div>
        </form>
    </div>
    
</div>
@endsection
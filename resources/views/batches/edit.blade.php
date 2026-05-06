@extends('layouts.app')

@section('title', 'Edit Batch | MangoWMS')
@section('title', 'Edit Batch')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('batches.index') }}" class="text-gray-400 hover:text-white">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-2xl font-bold text-white">
            <i class="fas fa-edit mr-2 text-indigo-400"></i>
            Edit Batch: {{ $batch->batch_number }}
        </h1>
    </div>
    
    <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
        <form action="{{ route('batches.update', $batch) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Product *</label>
                    <select name="product_id" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none" required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id', $batch->product_id) == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} ({{ $product->sku }})
                        </option>
                        @endforeach
                    </select>
                    @error('product_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Warehouse *</label>
                    <select name="warehouse_id" class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none" required>
                        <option value="">Select Warehouse</option>
                        @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $batch->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('warehouse_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Batch Number *</label>
                    <input type="text" name="batch_number" value="{{ old('batch_number', $batch->batch_number) }}" 
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none"
                           required>
                    @error('batch_number') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Quantity *</label>
                    <input type="number" name="quantity" value="{{ old('quantity', $batch->quantity) }}" 
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none"
                           required min="0">
                    @error('quantity') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Purchase Price *</label>
                    <input type="number" name="purchase_price" value="{{ old('purchase_price', $batch->purchase_price) }}" step="0.01"
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none"
                           required min="0">
                    @error('purchase_price') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Manufacture Date</label>
                    <input type="date" name="manufacture_date" value="{{ old('manufacture_date', $batch->manufacture_date ? \Carbon\Carbon::parse($batch->manufacture_date)->format('Y-m-d') : '') }}" 
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Expiry Date</label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date', $batch->expiry_date ? \Carbon\Carbon::parse($batch->expiry_date)->format('Y-m-d') : '') }}" 
                           class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none">
                    @error('expiry_date') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Notes</label>
                    <textarea name="notes" rows="3" 
                              class="w-full bg-slate-900 border border-slate-700 rounded-lg px-4 py-2 text-white focus:border-indigo-500 focus:outline-none">{{ old('notes', $batch->notes) }}</textarea>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-slate-700">
                <a href="{{ route('batches.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                    <i class="fas fa-save mr-2"></i> Update Batch
                </button>
            </div>
        </form>
    </div>
    
</div>
@endsection
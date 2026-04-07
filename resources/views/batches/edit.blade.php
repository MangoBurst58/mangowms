<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Batch') }}: {{ $batch->batch_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <form action="{{ route('batches.update', $batch) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium mb-2">Product *</label>
                                <select name="product_id" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 @error('product_id') border-red-500 @enderror" required>
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id', $batch->product_id) == $product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->sku }})
                                    </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2">Warehouse *</label>
                                <select name="warehouse_id" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 @error('warehouse_id') border-red-500 @enderror" required>
                                    <option value="">Select Warehouse</option>
                                    @foreach($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}" {{ old('warehouse_id', $batch->warehouse_id) == $warehouse->id ? 'selected' : '' }}>
                                        {{ $warehouse->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('warehouse_id')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2">Batch Number *</label>
                                <input type="text" name="batch_number" value="{{ old('batch_number', $batch->batch_number) }}" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 @error('batch_number') border-red-500 @enderror" required>
                                @error('batch_number')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2">Quantity *</label>
                                <input type="number" name="quantity" value="{{ old('quantity', $batch->quantity) }}" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 @error('quantity') border-red-500 @enderror" required min="0">
                                @error('quantity')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2">Purchase Price *</label>
                                <input type="number" name="purchase_price" value="{{ old('purchase_price', $batch->purchase_price) }}" step="0.01" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700 @error('purchase_price') border-red-500 @enderror" required min="0">
                                @error('purchase_price')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2">Manufacture Date</label>
                                <input type="date" name="manufacture_date" value="{{ old('manufacture_date', $batch->manufacture_date ? \Carbon\Carbon::parse($batch->manufacture_date)->format('Y-m-d') : '') }}" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium mb-2">Expiry Date</label>
                                <input type="date" name="expiry_date" value="{{ old('expiry_date', $batch->expiry_date ? \Carbon\Carbon::parse($batch->expiry_date)->format('Y-m-d') : '') }}" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700">
                                @error('expiry_date')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium mb-2">Notes</label>
                                <textarea name="notes" rows="3" class="w-full border rounded-lg px-3 py-2 dark:bg-gray-700">{{ old('notes', $batch->notes) }}</textarea>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex justify-end gap-3">
                            <a href="{{ route('batches.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">Cancel</a>
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">Update Batch</button>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <form action="{{ route('products.update', $product) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block">SKU *</label>
                                <input type="text" name="sku" value="{{ $product->sku }}" class="w-full border rounded px-2 py-1 dark:bg-gray-700" required>
                            </div>
                            
                            <div>
                                <label class="block">Barcode</label>
                                <input type="text" name="barcode" value="{{ $product->barcode }}" class="w-full border rounded px-2 py-1 dark:bg-gray-700">
                            </div>
                            
                            <div>
                                <label class="block">Product Name *</label>
                                <input type="text" name="name" value="{{ $product->name }}" class="w-full border rounded px-2 py-1 dark:bg-gray-700" required>
                            </div>
                            
                            <div>
                                <label class="block">Category</label>
                                <select name="category_id" class="w-full border rounded px-2 py-1 dark:bg-gray-700">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block">Unit *</label>
                                <select name="unit" class="w-full border rounded px-2 py-1 dark:bg-gray-700" required>
                                    <option value="pcs" {{ $product->unit == 'pcs' ? 'selected' : '' }}>Pieces (pcs)</option>
                                    <option value="box" {{ $product->unit == 'box' ? 'selected' : '' }}>Box</option>
                                    <option value="carton" {{ $product->unit == 'carton' ? 'selected' : '' }}>Carton</option>
                                    <option value="kg" {{ $product->unit == 'kg' ? 'selected' : '' }}>Kilogram (kg)</option>
                                    <option value="liter" {{ $product->unit == 'liter' ? 'selected' : '' }}>Liter</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block">Description</label>
                                <textarea name="description" rows="3" class="w-full border rounded px-2 py-1 dark:bg-gray-700">{{ $product->description }}</textarea>
                            </div>
                            
                            <div>
                                <label class="block">Purchase Price *</label>
                                <input type="number" name="purchase_price" step="0.01" value="{{ $product->purchase_price }}" class="w-full border rounded px-2 py-1 dark:bg-gray-700" required>
                            </div>
                            
                            <div>
                                <label class="block">Selling Price *</label>
                                <input type="number" name="selling_price" step="0.01" value="{{ $product->selling_price }}" class="w-full border rounded px-2 py-1 dark:bg-gray-700" required>
                            </div>
                            
                            <div>
                                <label class="block">Minimum Stock *</label>
                                <input type="number" name="min_stock" value="{{ $product->min_stock }}" class="w-full border rounded px-2 py-1 dark:bg-gray-700" required>
                            </div>
                            
                            <div>
                                <label class="block">Maximum Stock</label>
                                <input type="number" name="max_stock" value="{{ $product->max_stock }}" class="w-full border rounded px-2 py-1 dark:bg-gray-700">
                            </div>
                            
                            <div>
                                <label class="block">Reorder Point</label>
                                <input type="number" name="reorder_point" value="{{ $product->reorder_point }}" class="w-full border rounded px-2 py-1 dark:bg-gray-700">
                            </div>
                            
                            <div>
                                <label class="block">
                                    <input type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                                    Active
                                </label>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Product
                            </button>
                            <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                                Cancel
                            </a>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
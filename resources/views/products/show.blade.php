<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Product Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <strong>SKU:</strong> {{ $product->sku }}
                        </div>
                        <div>
                            <strong>Barcode:</strong> {{ $product->barcode ?? '-' }}
                        </div>
                        <div>
                            <strong>Name:</strong> {{ $product->name }}
                        </div>
                        <div>
                            <strong>Category:</strong> {{ $product->category->name ?? '-' }}
                        </div>
                        <div>
                            <strong>Unit:</strong> {{ $product->unit }}
                        </div>
                        <div>
                            <strong>Description:</strong> {{ $product->description ?? '-' }}
                        </div>
                        <div>
                            <strong>Purchase Price:</strong> Rp {{ number_format($product->purchase_price, 0) }}
                        </div>
                        <div>
                            <strong>Selling Price:</strong> Rp {{ number_format($product->selling_price, 0) }}
                        </div>
                        <div>
                            <strong>Minimum Stock:</strong> {{ $product->min_stock }}
                        </div>
                        <div>
                            <strong>Maximum Stock:</strong> {{ $product->max_stock ?? '-' }}
                        </div>
                        <div>
                            <strong>Reorder Point:</strong> {{ $product->reorder_point ?? '-' }}
                        </div>
                        <div>
                            <strong>Status:</strong> {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </div>
                        <div>
                            <strong>Total Stock:</strong> {{ $product->total_stock }}
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('products.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Products
                        </a>
                        <a href="{{ route('products.edit', $product) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">
                            Edit Product
                        </a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
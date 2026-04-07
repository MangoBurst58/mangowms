<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Batch Details') }}: {{ $batch->batch_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm text-gray-500">Batch Number</p>
                            <p class="font-medium">{{ $batch->batch_number }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Product</p>
                            <p class="font-medium">{{ $batch->product->name }} ({{ $batch->product->sku }})</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Warehouse</p>
                            <p class="font-medium">{{ $batch->warehouse->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Quantity</p>
                            <p class="font-medium">{{ number_format($batch->quantity) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Initial Quantity</p>
                            <p class="font-medium">{{ number_format($batch->initial_quantity) }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Purchase Price</p>
                            <p class="font-medium">Rp {{ number_format($batch->purchase_price, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Manufacture Date</p>
                            <p class="font-medium">{{ $batch->manufacture_date ? \Carbon\Carbon::parse($batch->manufacture_date)->format('d M Y') : '-' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Expiry Date</p>
                            <p class="font-medium">{{ $batch->expiry_date ? \Carbon\Carbon::parse($batch->expiry_date)->format('d M Y') : '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Notes</p>
                            <p class="font-medium">{{ $batch->notes ?? '-' }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">Status</p>
                            <p>
                                @php
                                    $isExpired = $batch->expiry_date && \Carbon\Carbon::parse($batch->expiry_date)->isPast();
                                    $isNearExpiry = $batch->expiry_date && \Carbon\Carbon::parse($batch->expiry_date)->diffInDays(now()) <= 30 && !$isExpired;
                                @endphp
                                @if($isExpired)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">Expired</span>
                                @elseif($isNearExpiry)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800">Near Expiry ({{ \Carbon\Carbon::parse($batch->expiry_date)->diffInDays(now()) }} days left)</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Active</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('batches.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition">Back</a>
                        <a href="{{ route('batches.edit', $batch) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition">Edit Batch</a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
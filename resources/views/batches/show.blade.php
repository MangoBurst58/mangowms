@extends('layouts.app')

@section('title', 'Batch Details')

@section('content')
<div class="max-w-4xl mx-auto">
    
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('batches.index') }}" class="text-gray-400 hover:text-white">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-2xl font-bold text-white">
            <i class="fas fa-info-circle mr-2 text-indigo-400"></i>
            Batch Details: {{ $batch->batch_number }}
        </h1>
    </div>
    
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-0">
            
            <div class="p-6 border-b md:border-b-0 md:border-r border-slate-700">
                <h2 class="text-lg font-semibold text-white mb-4">Basic Information</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-400">Batch Number</p>
                        <p class="text-white font-mono">{{ $batch->batch_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Product</p>
                        <p class="text-white">{{ $batch->product->name }} ({{ $batch->product->sku }})</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Warehouse</p>
                        <p class="text-white">{{ $batch->warehouse->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Quantity</p>
                        <p class="text-white text-2xl font-bold">{{ number_format($batch->quantity) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Initial Quantity</p>
                        <p class="text-white">{{ number_format($batch->initial_quantity) }}</p>
                    </div>
                </div>
            </div>
            
            <div class="p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Price & Dates</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-400">Purchase Price</p>
                        <p class="text-white">Rp {{ number_format($batch->purchase_price, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Manufacture Date</p>
                        <p class="text-white">{{ $batch->manufacture_date ? \Carbon\Carbon::parse($batch->manufacture_date)->format('d M Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Expiry Date</p>
                        <p class="text-white">{{ $batch->expiry_date ? \Carbon\Carbon::parse($batch->expiry_date)->format('d M Y') : '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Status</p>
                        @php
                            $isExpired = $batch->expiry_date && \Carbon\Carbon::parse($batch->expiry_date)->isPast();
                            $isNearExpiry = $batch->expiry_date && \Carbon\Carbon::parse($batch->expiry_date)->diffInDays(now()) <= 30 && !$isExpired;
                        @endphp
                        @if($isExpired)
                            <span class="px-2 py-1 text-xs rounded-full bg-red-900/50 text-red-300">Expired</span>
                        @elseif($isNearExpiry)
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-900/50 text-yellow-300">Near Expiry ({{ \Carbon\Carbon::parse($batch->expiry_date)->diffInDays(now()) }} days left)</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-emerald-900/50 text-emerald-300">Active</span>
                        @endif
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Notes</p>
                        <p class="text-white">{{ $batch->notes ?? '-' }}</p>
                    </div>
                </div>
            </div>
            
        </div>
        
        <div class="border-t border-slate-700 p-6">
            <h2 class="text-lg font-semibold text-white mb-4">Timeline</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-400">Created At</p>
                    <p class="text-white">{{ $batch->created_at->format('d M Y H:i:s') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-400">Last Updated</p>
                    <p class="text-white">{{ $batch->updated_at->format('d M Y H:i:s') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="flex justify-end gap-3 mt-6">
        <a href="{{ route('batches.index') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
        <a href="{{ route('batches.edit', $batch) }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
            <i class="fas fa-edit mr-2"></i> Edit Batch
        </a>
    </div>
    
</div>
@endsection
@extends('layouts.app')

@section('title', 'Stock Out Detail | MangoWMS')
@section('title', 'Stock Out Detail')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('stock-out.index') }}" class="text-gray-400 hover:text-white">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-2xl font-bold text-white">
            <i class="fas fa-arrow-up mr-2 text-red-400"></i>
            Stock Out Detail
        </h1>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Header Information -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Order Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-400">SO Number</p>
                        <p class="text-white font-mono">{{ $salesOrder->so_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Customer</p>
                        <p class="text-white">{{ $salesOrder->customer->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Warehouse</p>
                        <p class="text-white">{{ $salesOrder->warehouse->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Order Date</p>
                        <p class="text-white">{{ $salesOrder->order_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Delivery Date</p>
                        <p class="text-white">{{ $salesOrder->delivery_date->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Created By</p>
                        <p class="text-white">{{ $salesOrder->creator->name }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-400">Notes</p>
                        <p class="text-white">{{ $salesOrder->notes ?? '-' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Items -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Items Delivered</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-700">
                                <th class="text-left py-2 text-gray-400">Product</th>
                                <th class="text-left py-2 text-gray-400">Batch Number</th>
                                <th class="text-right py-2 text-gray-400">Quantity</th>
                                <th class="text-right py-2 text-gray-400">Unit Price</th>
                                <th class="text-right py-2 text-gray-400">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($salesOrder->items as $item)
                            <tr class="border-b border-slate-700/50">
                                <td class="py-3 text-white">{{ $item->product->name }}</td>
                                <td class="py-3 text-gray-300">{{ $item->batch->batch_number ?? '-' }}</td>
                                <td class="py-3 text-white text-right">{{ number_format($item->quantity) }}</td>
                                <td class="py-3 text-white text-right">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="py-3 text-white text-right">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t border-slate-700">
                                <td colspan="4" class="py-3 text-right font-bold text-white">Total:</td>
                                <td class="py-3 text-right font-bold text-white">Rp {{ number_format($salesOrder->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
        </div>
        
        <!-- Right Column - Status -->
        <div class="lg:col-span-1">
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6 sticky top-6">
                <h2 class="text-lg font-semibold text-white mb-4">Status</h2>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Status:</span>
                        <span class="px-2 py-1 text-xs rounded-full bg-emerald-900/50 text-emerald-300">Delivered</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Created At:</span>
                        <span class="text-white">{{ $salesOrder->created_at->format('d M Y H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Last Updated:</span>
                        <span class="text-white">{{ $salesOrder->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
</div>
@endsection
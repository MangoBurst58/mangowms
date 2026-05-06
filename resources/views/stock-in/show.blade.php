@extends('layouts.app')

@section('title', 'Stock In Detail | MangoWMS')
@section('title', 'Stock In Detail')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <div class="flex items-center gap-4 mb-6">
        <a href="{{ route('stock-in.index') }}" class="text-gray-400 hover:text-white">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <h1 class="text-2xl font-bold text-white">
            <i class="fas fa-arrow-down mr-2 text-emerald-400"></i>
            Stock In Detail
        </h1>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Header Information -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Receipt Information</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-400">GR Number</p>
                        <p class="text-white font-mono">{{ $goodsReceipt->gr_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">PO Number</p>
                        <p class="text-white font-mono">{{ $goodsReceipt->purchaseOrder->po_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Supplier</p>
                        <p class="text-white">{{ $goodsReceipt->purchaseOrder->supplier->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Warehouse</p>
                        <p class="text-white">{{ $goodsReceipt->warehouse->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Receipt Date</p>
                        <p class="text-white">{{ \Carbon\Carbon::parse($goodsReceipt->receipt_date)->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-400">Received By</p>
                        <p class="text-white">{{ $goodsReceipt->receiver->name }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-400">Notes</p>
                        <p class="text-white">{{ $goodsReceipt->notes ?? '-' }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Items Received - RAPI -->
            <div class="bg-slate-800 rounded-xl border border-slate-700 p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Items Received</h2>
                <div class="overflow-x-auto">
                    <table class="w-full" style="min-width: 700px">
                        <thead>
                            <tr class="border-b border-slate-700">
                                <th class="text-left py-2 text-gray-400 text-sm">Product</th>
                                <th class="text-left py-2 text-gray-400 text-sm">Batch Number</th>
                                <th class="text-right py-2 text-gray-400 text-sm">Quantity</th>
                                <th class="text-right py-2 text-gray-400 text-sm">Unit Price</th>
                                <th class="text-right py-2 text-gray-400 text-sm">Total</th>
                                <th class="text-left py-2 text-gray-400 text-sm">Expiry Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($goodsReceipt->items as $item)
                            <tr class="border-b border-slate-700/50">
                                <td class="py-3 text-white text-sm">{{ $item->product->name }}</td>
                                <td class="py-3 text-gray-300 text-sm">{{ $item->batch->batch_number ?? '-' }}</td>
                                <td class="py-3 text-white text-right text-sm">{{ number_format($item->quantity) }}</td>
                                <td class="py-3 text-white text-right text-sm">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="py-3 text-white text-right text-sm font-semibold">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                <td class="py-3 text-gray-300 text-sm">{{ $item->expiry_date ? \Carbon\Carbon::parse($item->expiry_date)->format('d M Y') : '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t border-slate-700">
                                <td colspan="4" class="py-3 text-right font-bold text-white">Total:</td>
                                <td class="py-3 text-right font-bold text-white">Rp {{ number_format($goodsReceipt->purchaseOrder->total_amount, 0, ',', '.') }}</td>
                                <td class="py-3"></td>
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
                        <span class="px-2 py-1 text-xs rounded-full bg-emerald-900/50 text-emerald-300">Completed</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Created At:</span>
                        <span class="text-white">{{ $goodsReceipt->created_at ? \Carbon\Carbon::parse($goodsReceipt->created_at)->format('d M Y H:i:s') : '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Last Updated:</span>
                        <span class="text-white">{{ $goodsReceipt->updated_at ? \Carbon\Carbon::parse($goodsReceipt->updated_at)->format('d M Y H:i:s') : '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
</div>
@endsection
@extends('layouts.app')

@section('title', 'Stock Out')

@section('content')
<div class="space-y-6">
    
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold text-white">
            <i class="fas fa-arrow-up mr-2 text-red-400"></i>
            Stock Out / Sales Orders
        </h1>
        <a href="{{ route('stock-out.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
            <i class="fas fa-plus mr-2"></i> New Stock Out
        </a>
    </div>
    
    @if(session('success'))
        <div class="bg-emerald-900/50 border border-emerald-700 text-emerald-300 px-4 py-3 rounded-lg">
            <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-900/50 border border-red-700 text-red-300 px-4 py-3 rounded-lg">
            <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
        </div>
    @endif
    
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-700">
                <thead>
                    <tr class="bg-slate-900/50">
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">SO Number</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Customer</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Warehouse</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Order Date</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Delivery Date</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Total Amount</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($salesOrders as $so)
                    <tr class="hover:bg-slate-700/50 transition">
                        <td class="px-6 py-4 text-white font-mono">{{ $so->so_number }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $so->customer->name }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $so->warehouse->name }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $so->order_date->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $so->delivery_date->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-white">Rp {{ number_format($so->total_amount, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full bg-emerald-900/50 text-emerald-300">Delivered</span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('stock-out.show', $so) }}" class="text-indigo-400 hover:text-indigo-300">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-truck text-4xl mb-3 block"></i>
                            No stock out records found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-slate-700">
            {{ $salesOrders->links() }}
        </div>
    </div>
    
</div>
@endsection
@extends('layouts.app')

@section('title', 'Batches | MangoWMS')
@section('title', 'Batches Management')

@section('content')
<div class="space-y-6">
    
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-xl font-semibold text-white">
            <i class="fas fa-layer-group mr-2 text-indigo-400"></i>
            Batches Management
        </h1>
        <a href="{{ route('batches.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
            <i class="fas fa-plus mr-2"></i> Add New Batch
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

    <!-- Table -->
    <div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-700">
                <thead>
                    <tr class="bg-slate-900/50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Batch Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Product</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Warehouse</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Quantity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Purchase Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Expiry Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    @forelse($batches as $batch)
                    <tr class="hover:bg-slate-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                            {{ $batch->batch_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            {{ $batch->product->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            {{ $batch->warehouse->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            {{ number_format($batch->quantity) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                            Rp {{ number_format($batch->purchase_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @php
                                $isExpired = $batch->expiry_date && \Carbon\Carbon::parse($batch->expiry_date)->isPast();
                                $isNearExpiry = $batch->expiry_date && \Carbon\Carbon::parse($batch->expiry_date)->diffInDays(now()) <= 30 && !$isExpired;
                            @endphp
                            @if($isExpired)
                                <span class="text-red-400">{{ \Carbon\Carbon::parse($batch->expiry_date)->format('d M Y') }}</span>
                            @elseif($isNearExpiry)
                                <span class="text-amber-400">{{ \Carbon\Carbon::parse($batch->expiry_date)->format('d M Y') }}</span>
                            @else
                                <span class="text-gray-300">{{ $batch->expiry_date ? \Carbon\Carbon::parse($batch->expiry_date)->format('d M Y') : '-' }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('batches.show', $batch) }}" class="text-indigo-400 hover:text-indigo-300 mr-2" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('batches.edit', $batch) }}" class="text-emerald-400 hover:text-emerald-300 mr-2" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('batches.destroy', $batch) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300" onclick="return confirm('Are you sure?')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-layer-group text-4xl mb-3 block"></i>
                            <p>No batches found</p>
                            <a href="{{ route('batches.create') }}" class="text-indigo-400 hover:text-indigo-300 text-sm mt-2 inline-block">
                                <i class="fas fa-plus mr-1"></i> Add your first batch
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(method_exists($batches, 'links'))
        <div class="px-6 py-4 border-t border-slate-700">
            {{ $batches->links() }}
        </div>
        @endif
    </div>
    
</div>
@endsection
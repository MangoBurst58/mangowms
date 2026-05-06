@extends('layouts.app')

@section('title', 'Dashboard | MangoWMS')
@section('title', 'Dashboard | MangoWMS')

@section('content')
<div class="space-y-6">
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <div class="group relative overflow-hidden bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-5 hover:border-indigo-500/70 transition-all duration-300 hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Total Products</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ number_format($totalProducts) }}</p>
                </div>
                <div class="w-10 h-10 bg-indigo-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-box text-indigo-400 text-xl"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="h-1 bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-500 rounded-full transition-all duration-500" style="width: 100%"></div>
                </div>
            </div>
        </div>
        
        <div class="group relative overflow-hidden bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-5 hover:border-emerald-500/70 transition-all duration-300 hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/10 to-teal-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Stock Value</p>
                    <p class="text-3xl font-bold text-white mt-1">Rp {{ number_format($totalStockValue, 0, ',', '.') }}</p>
                </div>
                <div class="w-10 h-10 bg-emerald-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-money-bill-wave text-emerald-400 text-xl"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="h-1 bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: 100%"></div>
                </div>
            </div>
        </div>
        
        <div class="group relative overflow-hidden bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-5 hover:border-amber-500/70 transition-all duration-300 hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-r from-amber-500/10 to-orange-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Low Stock</p>
                    <p class="text-3xl font-bold text-amber-400 mt-1">{{ $lowStockCount }}</p>
                </div>
                <div class="w-10 h-10 bg-amber-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-exclamation-triangle text-amber-400 text-xl"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="h-1 bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-amber-500 rounded-full transition-all duration-500" style="width: {{ $lowStockCount > 0 ? 25 : 0 }}%"></div>
                </div>
            </div>
        </div>
        
        <div class="group relative overflow-hidden bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-5 hover:border-blue-500/70 transition-all duration-300 hover:-translate-y-1">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-cyan-500/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wider">Warehouses</p>
                    <p class="text-3xl font-bold text-white mt-1">{{ $totalWarehouses }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-900/30 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-building text-blue-400 text-xl"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="h-1 bg-slate-700 rounded-full overflow-hidden">
                    <div class="h-full bg-blue-500 rounded-full transition-all duration-500" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stock Movement Chart -->
    <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 p-6 transition-all duration-300 hover:border-indigo-500/30">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
            <i class="fas fa-chart-line text-indigo-400"></i>
            Stock Movement (Last 7 Days)
        </h3>
        <canvas id="stockChart" class="w-full h-80"></canvas>
    </div>
    
    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 transition-all duration-300 hover:border-amber-500/30">
            <div class="px-5 py-4 border-b border-slate-700">
                <h2 class="text-sm font-semibold text-white">
                    <i class="fas fa-bell text-amber-500 mr-2"></i>
                    Low Stock Alert
                </h2>
            </div>
            <div class="p-5">
                @if($lowStockProducts->count() > 0)
                    <div class="space-y-3">
                        @foreach($lowStockProducts as $product)
                        <div class="flex justify-between items-center p-3 bg-slate-900/50 rounded-lg hover:bg-slate-900 transition-all duration-200">
                            <div>
                                <p class="font-medium text-white">{{ $product->name }}</p>
                                <p class="text-sm text-gray-400">SKU: {{ $product->sku }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-red-400 font-bold">Stock: {{ $product->current_stock }}</p>
                                <p class="text-sm text-gray-500">Min: {{ $product->min_stock }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-check-circle text-emerald-500 text-3xl mb-3"></i>
                        <p class="text-sm text-gray-400">All products have sufficient stock</p>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="bg-slate-800/50 backdrop-blur-sm rounded-xl border border-slate-700/50 transition-all duration-300 hover:border-blue-500/30">
            <div class="px-5 py-4 border-b border-slate-700">
                <h2 class="text-sm font-semibold text-white">
                    <i class="fas fa-history text-blue-500 mr-2"></i>
                    Recent Transactions
                </h2>
            </div>
            <div class="p-5">
                @if($recentTransactions->count() > 0)
                    <div class="space-y-3">
                        @foreach($recentTransactions as $transaction)
                        <div class="flex justify-between items-center p-3 border-b border-slate-700/50 last:border-0 hover:bg-slate-900/50 transition-all duration-200 rounded-lg">
                            <div>
                                <p class="font-medium text-white">{{ $transaction->transaction_number }}</p>
                                <p class="text-sm text-gray-400">{{ ucfirst(str_replace('_', ' ', $transaction->type)) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-400">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d M Y') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-inbox text-gray-500 text-3xl mb-3"></i>
                        <p class="text-sm text-gray-400">No transactions recorded</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('stockChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($chartData['labels']),
            datasets: [
                {
                    label: 'Stock In',
                    data: @json($chartData['in']),
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: 'rgb(34, 197, 94)',
                    pointBorderColor: 'white',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                },
                {
                    label: 'Stock Out',
                    data: @json($chartData['out']),
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: 'rgb(239, 68, 68)',
                    pointBorderColor: 'white',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        color: '#9ca3af',
                        usePointStyle: true,
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#1e293b',
                    titleColor: '#f3f4f6',
                    bodyColor: '#cbd5e1',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#334155',
                    },
                    ticks: {
                        color: '#9ca3af',
                    }
                },
                x: {
                    grid: {
                        color: '#334155',
                    },
                    ticks: {
                        color: '#9ca3af',
                    }
                }
            }
        }
    });
});
</script>
@endpush
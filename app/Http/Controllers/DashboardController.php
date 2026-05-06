<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Batch;
use App\Models\Warehouse;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Pastikan user memiliki company_id
        $companyId = $user->company_id ?? 1;
        
        // ============================================
        // KPI CARDS
        // ============================================
        
        // Total Products
        $totalProducts = Product::where('company_id', $companyId)->count();
        
        // Total Stock Value (dari semua batch)
        $totalStockValue = Batch::whereHas('warehouse', function($q) use ($companyId) {
            $q->where('company_id', $companyId);
        })->sum(DB::raw('quantity * purchase_price'));
        
        // Low Stock Count (produk dengan stok <= min_stock)
        $lowStockCount = Product::where('company_id', $companyId)
            ->whereRaw('(SELECT COALESCE(SUM(quantity), 0) FROM batches WHERE batches.product_id = products.id) <= min_stock')
            ->count();
        
        // Total Warehouses
        $totalWarehouses = Warehouse::where('company_id', $companyId)->count();
        
        // Data Warehouses untuk selector
        $warehouses = Warehouse::where('company_id', $companyId)->get();
        $warehouseId = $user->warehouse_id;
        
        // ============================================
        // LOW STOCK PRODUCTS
        // ============================================
        $lowStockProducts = Product::where('company_id', $companyId)
            ->where('is_active', true)
            ->with(['category', 'batches'])
            ->get()
            ->map(function($product) {
                $product->current_stock = $product->batches->sum('quantity');
                return $product;
            })
            ->filter(function($product) {
                return $product->current_stock <= $product->min_stock;
            })
            ->take(5);
        
        // ============================================
        // NEAR EXPIRY BATCHES
        // ============================================
        $nearExpiryBatches = Batch::with('product')
            ->whereHas('warehouse', function($q) use ($companyId) {
                $q->where('company_id', $companyId);
            })
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>=', now())
            ->where('quantity', '>', 0)
            ->orderBy('expiry_date', 'asc')
            ->limit(5)
            ->get();
        
        // ============================================
        // RECENT TRANSACTIONS (SEMENTARA KOSONG)
        // ============================================
        $recentTransactions = collect([]);
        
        // ============================================
        // TOP 5 PRODUCTS (SEMENTARA KOSONG)
        // ============================================
        $topProducts = collect([]);
        
        // ============================================
        // STOCK MOVEMENT CHART (SEMENTARA KOSONG)
        // ============================================
        // Stock Movement Chart (Last 7 days - REAL DATA)
$chartData = ['labels' => [], 'in' => [], 'out' => []];

// Ambil data stock_movement dari database
$movements = StockMovement::where('company_id', $user->company_id)
    ->where('movement_date', '>=', now()->subDays(7))
    ->get();

// Kelompokkan per tanggal
$dateRange = [];
for ($i = 6; $i >= 0; $i--) {
    $date = now()->subDays($i)->format('Y-m-d');
    $dateRange[$date] = [
        'label' => now()->subDays($i)->format('d M'),
        'in' => 0,
        'out' => 0
    ];
}

foreach ($movements as $movement) {
    $dateKey = $movement->movement_date->format('Y-m-d');
    if (isset($dateRange[$dateKey])) {
        if ($movement->type == 'in') {
            $dateRange[$dateKey]['in'] += $movement->quantity;
        } else {
            $dateRange[$dateKey]['out'] += $movement->quantity;
        }
    }
}

foreach ($dateRange as $data) {
    $chartData['labels'][] = $data['label'];
    $chartData['in'][] = $data['in'];
    $chartData['out'][] = $data['out'];
}
        
        // ============================================
        // RETURN VIEW
        // ============================================
        return view('dashboard', compact(
            'totalProducts', 'totalStockValue', 'lowStockCount', 'totalWarehouses',
            'lowStockProducts', 'nearExpiryBatches', 'recentTransactions',
            'topProducts', 'chartData', 'warehouses', 'warehouseId'
        ));
    }
}
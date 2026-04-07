<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Batch;
use App\Models\Warehouse;
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
        $chartData = ['labels' => [], 'in' => [], 'out' => []];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $chartData['labels'][] = $date->format('d M');
            $chartData['in'][] = 0;
            $chartData['out'][] = 0;
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
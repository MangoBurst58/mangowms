<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Exports\StockReportExport;
use Maatwebsite\Excel\Facades\Excel;

class StockReportController extends Controller
{
    public function index()
    {
        $products = Product::with('batches', 'category')
            ->where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get()
            ->map(function($product) {
                $product->current_stock = $product->batches->sum('quantity');
                return $product;
            });
        
        // Summary statistics
        $totalProducts = $products->count();
        $totalStock = $products->sum('current_stock');
        $totalValue = $products->sum(function($p) {
            return $p->current_stock * $p->purchase_price;
        });
        $lowStockCount = $products->filter(function($p) {
            return $p->current_stock <= $p->min_stock;
        })->count();
        
        return view('stock-report.index', compact('products', 'totalProducts', 'totalStock', 'totalValue', 'lowStockCount'));
    }
    
    public function export()
    {
        return Excel::download(new StockReportExport(auth()->user()->company_id), 'stock-report-' . date('Y-m-d') . '.xlsx');
    }
}
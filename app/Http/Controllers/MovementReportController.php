<?php

namespace App\Http\Controllers;

use App\Models\StockMovement;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MovementReportExport;

class MovementReportController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::with(['product', 'batch', 'warehouse', 'creator'])
            ->where('company_id', auth()->user()->company_id);
        
        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('movement_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('movement_date', '<=', $request->date_to);
        }
        
        $movements = $query->orderBy('movement_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Summary
        $totalIn = $query->clone()->where('type', 'in')->sum('quantity');
        $totalOut = $query->clone()->where('type', 'out')->sum('quantity');
        $totalValue = $query->clone()->sum('total_price');
        
        $products = Product::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
        
        return view('movement-report.index', compact('movements', 'products', 'totalIn', 'totalOut', 'totalValue'));
    }
    
    public function export(Request $request)
    {
        $query = StockMovement::with(['product', 'batch', 'warehouse', 'creator'])
            ->where('company_id', auth()->user()->company_id);
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('movement_date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('movement_date', '<=', $request->date_to);
        }
        
        $movements = $query->orderBy('movement_date', 'desc')->get();
        
        return Excel::download(new MovementReportExport($movements), 'movement-report-' . date('Y-m-d') . '.xlsx');
    }
}
<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StockReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $companyId;

    public function __construct($companyId)
    {
        $this->companyId = $companyId;
    }

    public function collection()
    {
        return Product::with('batches')
            ->where('company_id', $this->companyId)
            ->where('is_active', true)
            ->get()
            ->map(function($product) {
                $product->current_stock = $product->batches->sum('quantity');
                return $product;
            });
    }

    public function headings(): array
    {
        return [
            'SKU',
            'Product Name',
            'Category',
            'Unit',
            'Current Stock',
            'Minimum Stock',
            'Maximum Stock',
            'Status',
            'Purchase Price',
            'Selling Price'
        ];
    }

    public function map($product): array
    {
        $status = $product->current_stock <= $product->min_stock ? 'Low Stock' : 'Normal';
        if ($product->current_stock == 0) $status = 'Out of Stock';
        
        return [
            $product->sku,
            $product->name,
            $product->category->name ?? '-',
            $product->unit,
            $product->current_stock,
            $product->min_stock,
            $product->max_stock ?? '-',
            $status,
            $product->purchase_price,
            $product->selling_price,
        ];
    }
}
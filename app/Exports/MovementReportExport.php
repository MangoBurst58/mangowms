<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MovementReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $movements;

    public function __construct($movements)
    {
        $this->movements = $movements;
    }

    public function collection()
    {
        return $this->movements;
    }

    public function headings(): array
    {
        return [
            'Date',
            'Type',
            'Reference',
            'Product',
            'Batch Number',
            'Warehouse',
            'Quantity',
            'Unit Price',
            'Total',
            'Created By'
        ];
    }

    public function map($movement): array
    {
        return [
            $movement->movement_date->format('Y-m-d'),
            $movement->type == 'in' ? 'IN (Stock In)' : 'OUT (Stock Out)',
            $movement->reference_number ?? '-',
            $movement->product->name,
            $movement->batch->batch_number ?? '-',
            $movement->warehouse->name,
            $movement->quantity,
            $movement->unit_price,
            $movement->total_price,
            $movement->creator->name ?? '-',
        ];
    }
}
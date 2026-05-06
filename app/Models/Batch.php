<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Batch extends Model
{
    protected $fillable = [
        'product_id', 'warehouse_id', 'batch_number', 'manufacture_date',
        'expiry_date', 'quantity', 'initial_quantity', 'purchase_price', 'notes'
    ];

    protected $casts = [
        'manufacture_date' => 'date',
        'expiry_date' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Generate Batch Number otomatis
     * Format: BATCH-YYYYMMDD-001
     * Contoh: BATCH-20260506-001
     */
    public static function generateBatchNumber()
    {
        $date = date('Ymd');
        $prefix = 'BATCH-' . $date . '-';
        
        // Cari batch terakhir dengan prefix hari ini
        $lastBatch = self::where('batch_number', 'LIKE', $prefix . '%')
            ->orderBy('batch_number', 'desc')
            ->first();
        
        if ($lastBatch) {
            // Ambil 3 digit terakhir, increment
            $lastNumber = (int) substr($lastBatch->batch_number, -3);
            $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '001';
        }
        
        return $prefix . $newNumber;
    }
}
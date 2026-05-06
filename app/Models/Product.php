<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'company_id', 'category_id', 'sku', 'barcode', 'name', 'description',
        'unit', 'base_unit', 'purchase_price', 'selling_price', 'min_stock', 'max_stock',
        'reorder_point', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }
    
    public function getTotalStockAttribute(): int
    {
        return $this->batches()->sum('quantity');
    }
    
    /**
     * Generate SKU berdasarkan nama kategori
     * Format: {3 huruf pertama kategori}-{4 digit urutan}
     * Contoh: ELE-0001 (untuk kategori Elektronik)
     */
    public static function generateSKU($categoryName = null)
    {
        // Ambil 3 huruf pertama dari nama kategori (default: PRD)
        $prefix = 'PRD';
        if ($categoryName) {
            // Ambil 3 huruf pertama, hapus karakter non-huruf, uppercase
            $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $categoryName), 0, 3));
            if (strlen($prefix) < 3) {
                $prefix = str_pad($prefix, 3, 'X');
            }
        }
        
        // Cari SKU terakhir dengan prefix yang sama
        $lastSku = self::where('sku', 'LIKE', $prefix . '-%')
            ->orderBy('sku', 'desc')
            ->first();
        
        if ($lastSku) {
            // Ambil 4 digit terakhir dari SKU
            $lastNumber = (int) substr($lastSku->sku, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return $prefix . '-' . $newNumber;
    }
}
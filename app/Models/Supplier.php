<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'company_id',
        'code',
        'name',
        'tax_id',
        'email',
        'phone',
        'address',
        'contact_person',
        'contact_phone',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }
    
    /**
     * Generate Supplier Code otomatis
     * Format: SUP-0001, SUP-0002, dst
     */
    public static function generateCode()
    {
        $lastSupplier = self::where('code', 'LIKE', 'SUP-%')
            ->orderBy('code', 'desc')
            ->first();
        
        if ($lastSupplier) {
            $lastNumber = (int) substr($lastSupplier->code, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return 'SUP-' . $newNumber;
    }
}
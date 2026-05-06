<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'company_id', 'code', 'name', 'tax_id', 'email', 'phone',
        'address', 'contact_person', 'contact_phone', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function salesOrders(): HasMany
    {
        return $this->hasMany(SalesOrder::class);
    }
    
    /**
     * Generate Customer Code otomatis
     * Format: CUST-0001, CUST-0002, dst
     */
    public static function generateCode()
    {
        $lastCustomer = self::where('code', 'LIKE', 'CUST-%')
            ->orderBy('code', 'desc')
            ->first();
        
        if ($lastCustomer) {
            $lastNumber = (int) substr($lastCustomer->code, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        
        return 'CUST-' . $newNumber;
    }
}
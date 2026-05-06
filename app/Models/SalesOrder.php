<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesOrder extends Model
{
    protected $fillable = [
        'so_number', 'company_id', 'customer_id', 'warehouse_id',
        'created_by', 'order_date', 'delivery_date', 'status', 'notes', 'total_amount'
    ];

    protected $casts = [
        'order_date' => 'date',
        'delivery_date' => 'date',
        'total_amount' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SalesOrderItem::class);
    }

    public static function generateNumber()
    {
        $year = date('Y');
        $month = date('m');
        $last = self::where('so_number', 'LIKE', "SO/{$year}/{$month}/%")->orderBy('id', 'desc')->first();
        if ($last) {
            $lastNumber = (int) substr($last->so_number, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        return "SO/{$year}/{$month}/{$newNumber}";
    }
}
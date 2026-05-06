<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GoodsReceiptItem extends Model
{
    protected $fillable = [
        'goods_receipt_id',
        'purchase_order_item_id',
        'product_id',
        'batch_id',
        'quantity',
        'unit_price',
        'total_price',
        'expiry_date',
        'purchase_unit',        // Unit pembelian (box, carton, dus)
        'conversion_factor',    // 1 purchase_unit = conversion_factor × base_unit
        'base_quantity',        // quantity × conversion_factor (dalam base_unit)
        'base_unit_price',      // unit_price ÷ conversion_factor (harga per base_unit)
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'conversion_factor' => 'decimal:4',
        'base_unit_price' => 'decimal:2',
    ];

    public function goodsReceipt(): BelongsTo
    {
        return $this->belongsTo(GoodsReceipt::class);
    }

    public function purchaseOrderItem(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }
}
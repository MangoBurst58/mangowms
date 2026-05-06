<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('goods_receipt_items', function (Blueprint $table) {
            $table->string('purchase_unit', 50)->default('pcs')->after('quantity');
            $table->decimal('conversion_factor', 15, 4)->default(1)->after('purchase_unit');
            $table->decimal('base_quantity', 15, 4)->default(0)->after('conversion_factor');
            $table->decimal('base_unit_price', 15, 4)->default(0)->after('base_quantity');
        });
    }

    public function down(): void
    {
        Schema::table('goods_receipt_items', function (Blueprint $table) {
            $table->dropColumn(['purchase_unit', 'conversion_factor', 'base_quantity', 'base_unit_price']);
        });
    }
};
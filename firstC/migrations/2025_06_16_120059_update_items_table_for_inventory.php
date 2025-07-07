<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateItemsTableForInventory extends Migration
{
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->boolean('active')->default(true)->after('description');
            $table->decimal('tp', 10, 2)->nullable()->after('selling_price');
            $table->decimal('mrp', 10, 2)->nullable()->after('tp');
            $table->string('barcode')->nullable()->after('mrp');
            $table->decimal('tax_rate', 5, 2)->default(0)->after('barcode');
        });
    }

    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn(['active', 'tp', 'mrp', 'barcode', 'tax_rate']);
        });
    }
}
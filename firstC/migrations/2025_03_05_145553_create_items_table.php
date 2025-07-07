<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('item_code')->unique(); // Unique item code
            $table->string('item_name'); // Name of the item
            $table->decimal('item_purchase_price', 10, 2); // Purchase price with 10 digits total and 2 decimal places
            $table->decimal('mrp', 10, 2); // Maximum Retail Price (MRP)
            $table->decimal('tp', 10, 2); // Trade Price (TP)
            $table->integer('low_quantity'); // Low quantity alert threshold
            $table->boolean('status')->default(true); // Item status (enabled/disabled)
            $table->string('product_photo')->nullable(); // Path to the product photo (nullable)
            $table->string('company')->nullable(); // Company name (nullable)
            $table->timestamps(); // Adds `created_at` and `updated_at` columns
            $table->softDeletes(); // Adds `deleted_at` column for soft deletes
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items'); // Drop the table if the migration is rolled back
    }
};
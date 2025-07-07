<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->string('batch_number', 100)->nullable();
            $table->decimal('quantity', 12, 3); // Allows for small quantities (like 0.001g) or large quantities
            $table->decimal('remaining_quantity', 12, 3);
            $table->unsignedBigInteger('purchase_id')->nullable();
            $table->unsignedBigInteger('purchase_item_id')->nullable();
            $table->unsignedBigInteger('sale_id')->nullable();
            $table->unsignedBigInteger('sale_item_id')->nullable();
            $table->unsignedBigInteger('purchase_return_id')->nullable();
            $table->unsignedBigInteger('sale_return_id')->nullable();
            $table->enum('movement_type', ['purchase', 'sale', 'purchase_return', 'sale_return', 'adjustment', 'waste', 'transfer']);
            $table->date('movement_date');
            $table->date('expiry_date')->nullable();
            $table->decimal('unit_cost', 12, 3); // Cost per unit
            $table->decimal('unit_price', 12, 3); // Selling price per unit
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('purchase_id')->references('id')->on('purchases')->onDelete('cascade');
            $table->foreign('purchase_item_id')->references('id')->on('purchase_items')->onDelete('cascade');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('sale_item_id')->references('id')->on('sale_items')->onDelete('cascade');
            $table->foreign('purchase_return_id')->references('id')->on('purchase_returns')->onDelete('cascade');
            $table->foreign('sale_return_id')->references('id')->on('sale_returns')->onDelete('cascade');

            // Indexes
            $table->index(['item_id', 'batch_number']);
            $table->index(['expiry_date']);
            $table->index(['movement_type', 'movement_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventories');
    }
}
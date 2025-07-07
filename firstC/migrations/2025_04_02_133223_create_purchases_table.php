<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained();
            $table->string('company_invoice')->nullable();
            $table->string('invoice_number')->unique();
            $table->date('invoice_date');
            $table->decimal('total_purchases', 10, 2);
            $table->decimal('net_payable', 10, 2);
            $table->decimal('prev_balance', 10, 2)->default(0);
            $table->decimal('total_balance', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('purchases');
    }
};
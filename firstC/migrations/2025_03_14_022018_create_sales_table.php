<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->date('invoice_date');
            $table->string('invoice_number');
            $table->string('username');
            $table->decimal('total_sales', 10, 2);
            $table->decimal('net_payable', 10, 2);
            $table->decimal('prev_balance', 10, 2);
            $table->decimal('total_balance', 10, 2);
            $table->timestamps();
        
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });

        
    }

    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('company')->nullable();
            $table->decimal('credit_limit', 10, 2)->nullable();
            $table->string('area')->nullable();
            $table->string('brick')->nullable();
            $table->string('salesman')->nullable();
            $table->boolean('usd')->default(false);
            $table->boolean('afn')->default(false);
            $table->boolean('pkr')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};

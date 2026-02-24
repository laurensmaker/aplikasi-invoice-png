<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();
            $table->date('invoice_date');            
            $table->string('invoice_number'); 
            $table->string('from_company');
            $table->string('from_address');
            $table->string('to_company');
            $table->string('to_address');
            $table->bigInteger('total_amount')->nullable();     
            $table->string('signature')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};

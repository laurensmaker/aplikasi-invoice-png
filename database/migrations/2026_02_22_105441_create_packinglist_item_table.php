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
        Schema::create('packinglist_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('packing_list_id')
            ->constrained('packinglist')
            ->onDelete('cascade');
            $table->integer('qty');                      
            $table->string('description');               
            $table->integer('weight');                    
            $table->integer('total_weight');  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packinglist_item');
    }
};

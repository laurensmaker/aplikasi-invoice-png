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
        Schema::create('surat_jalan', function (Blueprint $table) {
            $table->id();
            $table->string('surat_jalan_number');
            $table->string('nomor_peb');
            $table->date('tanggal_peb');
            $table->string('nomor_polisi');            
            $table->string('nama_supir');            
            $table->string('jenis_kendaraan');            
            $table->foreignId('invoice_id')->constrained('invoice')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_jalan');
    }
};

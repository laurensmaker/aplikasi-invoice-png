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
        Schema::create('pengangkutan', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_polisi');
            $table->string('nama_supir');
            $table->string('jenis_kendaraan');
            $table->foreignId('surat_jalan_id')->constrained('surat_jalan')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengangkutan');
    }
};

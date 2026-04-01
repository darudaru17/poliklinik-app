<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('daftar_poli', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pasien');
            $table->unsignedBigInteger('id_jadwal');
            $table->text('keluhan')->nullable();
            $table->integer('no_antrian');
            $table->timestamps();

            $table->foreign('id_pasien')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('id_jadwal')->references('id')->on('jadwal_periksa')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('daftar_poli'); }
};
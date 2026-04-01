<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('periksa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_daftar_poli');
            $table->dateTime('tgl_periksa');
            $table->text('catatan')->nullable();
            $table->integer('biaya_periksa')->default(0);
            $table->timestamps();

            $table->foreign('id_daftar_poli')->references('id')->on('daftar_poli')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('periksa'); }
};
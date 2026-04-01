<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('detail_periksa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_periksa');
            $table->unsignedBigInteger('id_obat');
            $table->timestamps();

            $table->foreign('id_periksa')->references('id')->on('periksa')->cascadeOnDelete();
            $table->foreign('id_obat')->references('id')->on('obat')->cascadeOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('detail_periksa'); }
};
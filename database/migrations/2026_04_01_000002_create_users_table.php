<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('alamat')->nullable();
            $table->string('no_ktp')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('no_rm')->nullable();
            $table->enum('role', ['admin', 'dokter', 'pasien']);
            $table->unsignedBigInteger('id_poli')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();

            $table->foreign('id_poli')->references('id')->on('poli')->nullOnDelete();
        });
    }
    public function down(): void { Schema::dropIfExists('users'); }
};
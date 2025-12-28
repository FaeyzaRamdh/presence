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
    Schema::create('presences', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->time('jam_masuk')->nullable();
        $table->time('jam_keluar')->nullable();
        $table->string('status')->default('hadir'); // hadir, izin, sakit
        $table->string('foto')->nullable(); // foto selfie opsional
        $table->timestamps();
    });
}

public function down(): void
{
    Schema::dropIfExists('presences');
}
};
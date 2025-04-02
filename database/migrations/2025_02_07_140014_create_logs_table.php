<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // user_id nullable
            $table->string('action');
            $table->text('description')->nullable();
            $table->string('user_agent')->nullable(); // Untuk menyimpan informasi user-agent
            $table->string('external_id')->nullable(); // Untuk log tanpa user_id (misalnya callback dari API)
            $table->string('category')->nullable(); // Menambahkan kategori untuk log
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};

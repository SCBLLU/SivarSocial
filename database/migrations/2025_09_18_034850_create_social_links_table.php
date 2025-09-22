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
        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('platform'); // instagram, github, discord, twitter, etc.
            $table->string('url'); // El enlace completo
            $table->string('username')->nullable(); // El username extraído de la URL
            $table->string('icon')->nullable(); // Ícono a usar (calculado automáticamente)
            $table->integer('order')->default(0); // Para ordenar los enlaces
            $table->timestamps();
            
            // Índices
            $table->index(['user_id', 'order']);
            $table->unique(['user_id', 'platform']); // Un usuario no puede tener dos enlaces de la misma plataforma
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_links');
    }
};

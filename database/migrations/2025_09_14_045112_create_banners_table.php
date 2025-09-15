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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Título del banner
            $table->text('content'); // Contenido del banner
            $table->enum('type', ['info', 'feature', 'update'])->default('info'); // Solo 3 tipos permitidos
            $table->string('image_url')->nullable(); // URL de imagen opcional
            $table->string('action_text')->nullable(); // Texto del botón de acción
            $table->string('action_url')->nullable(); // URL del botón de acción
            $table->boolean('is_active')->default(true); // Si el banner está activo/inactivo
            $table->timestamp('start_date')->nullable(); // Fecha de inicio
            $table->timestamp('end_date')->nullable(); // Fecha fin
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};

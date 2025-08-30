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
        Schema::table('likes', function (Blueprint $table) {
            // Índice para consultas por post_id (para obtener likes de un post)
            $table->index('post_id');
            // Índice para consultas por user_id (para obtener likes de un usuario)
            $table->index('user_id');
            // Índice compuesto para verificar si un usuario ya dio like a un post
            $table->unique(['user_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('likes', function (Blueprint $table) {
            $table->dropIndex(['post_id']);
            $table->dropIndex(['user_id']);
            $table->dropUnique(['user_id', 'post_id']);
        });
    }
};

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
        Schema::table('comentarios', function (Blueprint $table) {
            // parent_id: referencia a la misma tabla (nullable)
            $table->unsignedBigInteger('parent_id')->nullable()->after('user_id');
            $table->foreign('parent_id')->references('id')->on('comentarios')->onDelete('set null');

            // depth: profundidad del comentario
            $table->unsignedSmallInteger('depth')->default(0)->after('parent_id');

            // reply_count: contador de respuestas directas
            $table->unsignedInteger('reply_count')->default(0)->after('depth');

            // Índices para mejorar consultas por post y padre
            $table->index(['post_id', 'parent_id']);
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comentarios', function (Blueprint $table) {
            $table->dropIndex(['post_id', 'parent_id']);
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'depth', 'reply_count']);
        });
    }
};

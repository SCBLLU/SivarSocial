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
        Schema::create('banner_user_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('banner_id')->constrained()->onDelete('cascade');
            $table->timestamp('viewed_at')->useCurrent();
            $table->timestamps();
            
            // Índice único para evitar duplicados
            $table->unique(['user_id', 'banner_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_user_views');
    }
};

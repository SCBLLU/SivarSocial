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
        Schema::table('banners', function (Blueprint $table) {
            // Actualizar la columna type para que solo permita info, feature, update
            $table->enum('type', ['info', 'feature', 'update'])->default('info')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            // Revertir a string normal
            $table->string('type')->default('info')->change();
        });
    }
};

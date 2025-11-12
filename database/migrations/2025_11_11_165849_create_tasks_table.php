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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid_cliente')->nullable()->index();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->timestamp('due_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('estimated_pomodoros')->default(1);
            $table->integer('completed_pomodoros')->default(0);
            $table->timestamp('synced_at')->nullable();
            $table->timestamps();

            // Índices para mejor rendimiento
            $table->index(['user_id', 'status']);
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

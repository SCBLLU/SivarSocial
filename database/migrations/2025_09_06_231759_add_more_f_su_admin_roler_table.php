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
        Schema::table('su_ad', function (Blueprint $table) {
            $table->boolean('is_admin')->default(true)->after('password');
            $table->timestamp('last_login')->nullable()->after('is_admin');
            $table->string('imagen')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('su_ad', function (Blueprint $table) {
            $table->dropColumn(['is_admin', 'last_login', 'imagen']);
        });
    }
};

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
        Schema::table('users', function (Blueprint $table) {
            // Social Login
            $table->string('google_id')->nullable()->unique();
            $table->string('provider')->nullable();

            // User Metadata
            $table->string('join_date')->nullable();
            $table->string('last_login')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('status')->nullable();
            $table->string('avatar')->nullable();

            // Modify the existing password column to be nullable
            $table->string('password')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('google_id');
            $table->string('password')->nullable(false)->change();
        });
    }
};

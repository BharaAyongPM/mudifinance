<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 20)->default('user');
            $table->boolean('is_active')->default(true);
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('business_units', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('business_units', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('accounts', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_active']);
        });
    }
};

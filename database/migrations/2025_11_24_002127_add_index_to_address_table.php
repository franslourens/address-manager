<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->index('status', 'status_idx');
            $table->index('deleted_at', 'deleted_at_idx');
            $table->index('by_user_id', 'by_user_idx');
            $table->index('country_code', 'country_code_idx');
            $table->index('city', 'city_idx');
            $table->index('postal', 'postal_idx');
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            $table->dropIndex('status_idx');
            $table->dropIndex('deleted_at_idx');
            $table->dropIndex('by_user_idx');
            $table->dropIndex('country_code_idx');
            $table->dropIndex('city_idx');
            $table->dropIndex('postal_idx');
        });
    }
};
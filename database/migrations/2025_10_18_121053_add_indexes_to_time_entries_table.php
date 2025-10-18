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
        Schema::table('time_entries', function (Blueprint $table) {
            // Add indexes for frequently queried columns
            $table->index('start');
            $table->index('end');
            $table->index('created_at');

            // Composite index for finding ongoing time entries by user
            $table->index(['user_id', 'end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_entries', function (Blueprint $table) {
            // Drop indexes in reverse order
            $table->dropIndex(['user_id', 'end']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['end']);
            $table->dropIndex(['start']);
        });
    }
};

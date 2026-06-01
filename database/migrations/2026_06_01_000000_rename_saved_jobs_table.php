<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only rename if the incorrect table exists and the correct one does not
        if (Schema::hasTable('saved_jobs_') && !Schema::hasTable('saved_jobs')) {
            Schema::rename('saved_jobs_', 'saved_jobs');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('saved_jobs') && !Schema::hasTable('saved_jobs_')) {
            Schema::rename('saved_jobs', 'saved_jobs_');
        }
    }
};

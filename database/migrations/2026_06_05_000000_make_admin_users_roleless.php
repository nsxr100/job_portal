<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE users MODIFY role VARCHAR(255) NULL DEFAULT NULL');
        DB::table('users')->where('is_admin', true)->update(['role' => null]);
    }

    public function down(): void
    {
        DB::table('users')->whereNull('role')->update(['role' => 'applicant']);
        DB::statement("ALTER TABLE users MODIFY role VARCHAR(255) NOT NULL DEFAULT 'applicant'");
    }
};

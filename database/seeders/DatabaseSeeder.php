<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Job;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. CREATE ADMIN ACCOUNT
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'), // Automatically hashed safely
            'is_admin' => true, // Sets the admin flag helper
        ]);

        // 2. CREATE REGULAR USER ACCOUNT
        User::create([
            'name' => 'John Doe',
            'email' => 'user@example.com',
            'password' => Hash::make('user123'), // Automatically hashed safely
            'is_admin' => false,
        ]);

        // 3. CREATE SAMPLE JOB POSTINGS (CRUD Test Data)
        Job::create([
            'user_id'=>1,
            'title' => 'Laravel Backend Developer',
            'company' => 'TechCorp Solutions',
            'location' => 'Remote',
            'salary' => '$80,000 - $100,000/yr',
            'type' => 'Full-Time',
            'description' => "We are looking for a skilled Laravel developer to join our engineering team to build scalable RESTful APIs.\n\nResponsibilities:\n• Develop and maintain robust backend API endpoints.\n• Optimize database queries using Eloquent ORM.\n• Write clean, documented PHP code.",
        ]);

        Job::create([
            'user_id'=>2,
            'title' => 'Full Stack Engineer (Vue + Laravel)',
            'company' => 'StartupHub',
            'location' => 'Austin, TX',
            'salary' => '$60 - $80/hr',
            'type' => 'Contract',
            'description' => "Join us in expanding our core portal systems. Experience with Blade component architecture and Inertia.js is a plus.\n\nRequirements:\n• 2+ years of experience with Laravel frameworks.\n• Strong frontend skills with Vue.js or Tailwind CSS.\n• Ability to work independently in a fast-paced environment.",
        ]);

        Job::create([
            'user_id'=>1,
            'title' => 'Junior QA Automation Engineer',
            'company' => 'Global Systems Inc.',
            'location' => 'New York, NY',
            'salary' => '$65,000/yr',
            'type' => 'Part-Time',
            'description' => "Excellent entry-level opportunity to learn test automation workflows using Laravel Dusk and PHPUnit within continuous integration deployment frameworks.",
        ]);

        Job::create([
            'user_id'=>2,
            'title' => 'Senior DevOps Specialist',
            'company' => 'CloudScale Operations',
            'location' => 'Remote',
            'salary' => '$140,000 - $160,000/yr',
            'type' => 'Remote',
            'description' => "Manage cluster infrastructures, container workflows, and optimize high-availability deployments. Experience creating fine-tuned production environments and Dockerfile orchestration maps is mandatory.",
        ]);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    // Forces the model to use your custom table
    protected $table = 'portal_jobs'; 

    // FIXED: Added company, location, salary, and type to match your seeder and migration
    protected $fillable = [
        'user_id', 
        'title', 
        'company', 
        'location', 
        'salary', 
        'type', 
        'description', 
        'category'
    ];
    
    public function employer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function applications()
    {
        return $this->hasMany(Application::class, 'job_id');
    }
    
    public function savedByUsers()
    {
        return $this->belongsToMany(User::class, 'saved_jobs', 'job_id', 'user_id');
    }
}
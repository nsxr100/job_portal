<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $fillable = ['job_id', 'user_id', 'status'];

    
    public function job()
    {
        return $this->belongsTo(Job::class, 'job_id');
    }

    
    public function applicant()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

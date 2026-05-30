<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password','is_admin','role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',   
            'is_admin'=>'boolean'
        ];
    }
    
public function jobs() {
    return $this->hasMany(Job::class);
}


public function applications() {
    return $this->hasMany(Application::class);
}


public function resume() {
    return $this->hasOne(\App\Models\Resume::class);
}


public function savedJobs() {
    return $this->belongsToMany(Job::class, 'saved_jobs');
}

}

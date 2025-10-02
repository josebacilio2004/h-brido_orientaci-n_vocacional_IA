<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
        ];
    }
    
    public function testResponses()
    {
        return $this->hasMany(TestResponse::class);
    }
    
    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }
    
    public function grades()
    {
        return $this->hasOne(StudentGrade::class);
    }
    
    public function aiPredictions()
    {
        return $this->hasMany(AIPrediction::class);
    }
    
    public function getLatestPrediction()
    {
        return $this->aiPredictions()->latest()->first();
    }
    
    public function getCompletedTestsCount()
    {
        return $this->testResults()->count();
    }
}

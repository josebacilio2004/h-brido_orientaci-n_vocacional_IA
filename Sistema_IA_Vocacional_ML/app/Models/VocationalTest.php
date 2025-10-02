<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VocationalTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'duration_minutes',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function questions()
    {
        return $this->hasMany(TestQuestion::class)->orderBy('order');
    }

    public function responses()
    {
        return $this->hasMany(TestResponse::class);
    }

    public function results()
    {
        return $this->hasMany(TestResult::class);
    }
}

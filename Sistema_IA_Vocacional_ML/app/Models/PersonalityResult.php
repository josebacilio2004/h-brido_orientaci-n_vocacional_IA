<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalityResult extends Model
{
    protected $table = 'personality_results';

    protected $fillable = [
        'user_id',
        'test_personality_id',
        'scores',
        'recommended_careers',
        'analysis',
        'total_score',
        'completed_at'
    ];

    protected $casts = [
        'scores' => 'array',
        'recommended_careers' => 'array',
        'completed_at' => 'datetime',
    ];
}

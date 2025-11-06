<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterestResult extends Model
{
    protected $fillable = [
        'user_id',
        'test_interest_id',
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalityQuestion extends Model
{
    protected $fillable = [
        'test_personality_id',
        'question_number',
        'question',
        'trait',
        'options',
        'display_order'
    ];

    protected $casts = [
        'options' => 'array',
    ];
}

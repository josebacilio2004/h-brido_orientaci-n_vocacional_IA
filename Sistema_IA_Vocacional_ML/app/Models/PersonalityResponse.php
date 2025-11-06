<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonalityResponse extends Model
{
    protected $fillable = [
        'user_id',
        'test_personality_id',
        'personality_question_id',
        'answer',
        'score'
    ];
}

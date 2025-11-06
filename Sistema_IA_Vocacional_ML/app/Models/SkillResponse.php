<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillResponse extends Model
{
    protected $fillable = [
        'user_id',
        'test_skill_id',
        'skill_question_id',
        'answer',
        'score'
    ];
}

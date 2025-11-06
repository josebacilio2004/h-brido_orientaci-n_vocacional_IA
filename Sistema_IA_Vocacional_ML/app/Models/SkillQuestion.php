<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkillQuestion extends Model
{
    protected $fillable = [
        'test_skill_id',
        'question_number',
        'question',
        'skill_category',
        'options',
        'display_order'
    ];

    protected $casts = [
        'options' => 'array',
    ];
}

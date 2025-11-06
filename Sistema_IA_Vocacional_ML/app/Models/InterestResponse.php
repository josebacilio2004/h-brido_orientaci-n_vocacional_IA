<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterestResponse extends Model
{
    protected $fillable = [
        'user_id',
        'test_interest_id',
        'interest_question_id',
        'answer',
        'score'
    ];
}

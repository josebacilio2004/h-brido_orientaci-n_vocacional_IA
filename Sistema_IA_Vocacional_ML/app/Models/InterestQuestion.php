<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterestQuestion extends Model
{
    protected $fillable = [
        'test_interest_id',
        'question_number',
        'question',
        'category',
        'options',
        'display_order'
    ];

    protected $casts = [
        'options' => 'array',
    ];
}

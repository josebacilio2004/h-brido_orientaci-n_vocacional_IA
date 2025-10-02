<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'category',
        'required_skills',
        'related_subjects',
        'average_salary',
        'duration_years',
        'job_opportunities',
        'image_url'
    ];

    protected $casts = [
        'required_skills' => 'array',
        'related_subjects' => 'array',
        'average_salary' => 'decimal:2',
    ];
}

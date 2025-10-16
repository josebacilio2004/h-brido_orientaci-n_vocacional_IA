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
        'faculty', // Agregado
        'university', // Agregado
        'campus', // Agregado
        'riasec_profile', // Agregado
        'riasec_scores', // Agregado
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
        'riasec_scores' => 'array', // Agregado
        'average_salary' => 'decimal:2',
    ];
}

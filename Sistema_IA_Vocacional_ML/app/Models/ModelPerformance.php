<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelPerformance extends Model
{
    protected $fillable = [
        'model_name',
        'accuracy',
        'precision',
        'recall',
        'f1_score',
        'confusion_matrix',
        'trained_at',
        'notes'
    ];

    protected $casts = [
        'confusion_matrix' => 'array',
        'trained_at' => 'datetime',
    ];
}

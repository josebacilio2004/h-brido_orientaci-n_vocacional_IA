<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MLPrediction extends Model
{
    protected $fillable = [
        'user_id',
        'prediction_type',
        'features',
        'predicted_careers',
        'confidence_score',
        'model_metadata'
    ];

    protected $casts = [
        'features' => 'array',
        'predicted_careers' => 'array',
        'model_metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

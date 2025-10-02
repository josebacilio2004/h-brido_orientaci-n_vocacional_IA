<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIPrediction extends Model
{
    use HasFactory;

    protected $table = 'ai_predictions';

    protected $fillable = [
        'user_id',
        'input_data',
        'predicted_career',
        'confidence',
        'top_careers',
        'model_version'
    ];

    protected $casts = [
        'input_data' => 'array',
        'top_careers' => 'array',
        'confidence' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

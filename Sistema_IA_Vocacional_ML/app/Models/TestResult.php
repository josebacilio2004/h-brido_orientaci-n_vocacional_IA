<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vocational_test_id',
        'scores',
        'recommended_careers',
        'analysis',
        'total_score',
        'completed_at'
    ];

    protected $casts = [
        'scores' => 'array',
        'recommended_careers' => 'array',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function test()
    {
        return $this->belongsTo(VocationalTest::class, 'vocational_test_id');
    }
}

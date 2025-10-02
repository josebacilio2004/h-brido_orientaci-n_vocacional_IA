<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'vocational_test_id',
        'question',
        'type',
        'options',
        'category',
        'order'
    ];

    protected $casts = [
        'options' => 'array',
    ];

    public function test()
    {
        return $this->belongsTo(VocationalTest::class, 'vocational_test_id');
    }

    public function responses()
    {
        return $this->hasMany(TestResponse::class);
    }
}

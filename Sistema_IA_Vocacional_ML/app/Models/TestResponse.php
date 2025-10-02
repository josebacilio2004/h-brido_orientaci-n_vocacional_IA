<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vocational_test_id',
        'test_question_id',
        'answer',
        'score'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function test()
    {
        return $this->belongsTo(VocationalTest::class, 'vocational_test_id');
    }

    public function question()
    {
        return $this->belongsTo(TestQuestion::class, 'test_question_id');
    }
}

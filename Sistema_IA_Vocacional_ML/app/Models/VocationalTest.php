<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VocationalTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',           // Asegúrate de que sea 'name' y no 'title'
        'description', 
        'type',
        'duration_minutes',
        'total_questions', // Agregar este campo si no existe
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'total_questions' => 'integer',
    ];

    public function questions()
    {
        return $this->hasMany(TestQuestion::class)->orderBy('question_number'); // Cambiar 'order' por 'question_number'
    }

    public function responses()
    {
        return $this->hasMany(TestResponse::class);
    }

    public function results()
    {
        return $this->hasMany(TestResult::class);
    }
}
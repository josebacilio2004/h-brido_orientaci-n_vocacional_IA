<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StudentGrade;
use App\Models\User;

class StudentGradesSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener estudiantes (usuarios con rol 'student')
        $students = User::where('role', 'student')->get();

        foreach ($students as $student) {
            StudentGrade::create([
                'user_id' => $student->id,
                'nota_matematica' => rand(12, 20),
                'nota_comunicacion' => rand(12, 20),
                'nota_ciencias_sociales' => rand(12, 20),
                'nota_ciencia_tecnologia' => rand(12, 20),
                'nota_desarrollo_personal' => rand(12, 20),
                'nota_ciudadania_civica' => rand(12, 20),
                'nota_educacion_fisica' => rand(12, 20),
                'nota_ingles' => rand(12, 20),
                'nota_educacion_trabajo' => rand(12, 20),
                'academic_year' => 2024,
            ]);
        }
    }
}

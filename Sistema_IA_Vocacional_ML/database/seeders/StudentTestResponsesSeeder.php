<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\VocationalTest;
use App\Models\TestQuestion;
use App\Models\TestResponse;
use App\Models\TestResult;
use Illuminate\Support\Facades\Hash;

class StudentTestResponsesSeeder extends Seeder
{
    /**
     * Seed 500 estudiantes ficticios con respuestas de test vocacional
     */
    public function run(): void
    {
        $test = VocationalTest::where('type', 'riasec')->first();

        if (!$test) {
            $this->command->error('No se encontró el test RIASEC. Ejecuta VocationalTestSeeder primero.');
            return;
        }

        $questions = TestQuestion::where('vocational_test_id', $test->id)
            ->orderBy('question_number')
            ->get();

        if ($questions->count() === 0) {
            $this->command->error('No hay preguntas en el test RIASEC.');
            return;
        }

        $this->command->info('Generando 500 estudiantes con respuestas de test vocacional...');

        // Definir perfiles RIASEC con diferentes distribuciones
        $profiles = [
            'realista' => ['realista' => 0.8, 'investigador' => 0.3, 'artistico' => 0.2, 'social' => 0.3, 'emprendedor' => 0.4, 'convencional' => 0.5],
            'investigador' => ['realista' => 0.3, 'investigador' => 0.9, 'artistico' => 0.4, 'social' => 0.3, 'emprendedor' => 0.3, 'convencional' => 0.4],
            'artistico' => ['realista' => 0.2, 'investigador' => 0.4, 'artistico' => 0.9, 'social' => 0.5, 'emprendedor' => 0.4, 'convencional' => 0.2],
            'social' => ['realista' => 0.3, 'investigador' => 0.3, 'artistico' => 0.5, 'social' => 0.9, 'emprendedor' => 0.5, 'convencional' => 0.3],
            'emprendedor' => ['realista' => 0.4, 'investigador' => 0.3, 'artistico' => 0.4, 'social' => 0.5, 'emprendedor' => 0.9, 'convencional' => 0.6],
            'convencional' => ['realista' => 0.5, 'investigador' => 0.4, 'artistico' => 0.2, 'social' => 0.3, 'emprendedor' => 0.6, 'convencional' => 0.9],
        ];

        $nombres = ['Juan', 'María', 'Carlos', 'Ana', 'Luis', 'Carmen', 'José', 'Laura', 'Miguel', 'Sofia', 'Pedro', 'Isabel', 'Diego', 'Elena', 'Javier'];
        $apellidos = ['García', 'Rodríguez', 'Martínez', 'López', 'González', 'Pérez', 'Sánchez', 'Ramírez', 'Torres', 'Flores', 'Rivera', 'Gómez', 'Díaz', 'Cruz', 'Morales'];

        for ($i = 1; $i <= 500; $i++) {
            // Crear usuario ficticio
            $nombre = $nombres[array_rand($nombres)];
            $apellido = $apellidos[array_rand($apellidos)];
            $email = strtolower($nombre . '.' . $apellido . $i . '@estudiante.com');

            $user = User::create([
                'name' => $nombre . ' ' . $apellido,
                'email' => $email,
                'password' => Hash::make('password123'),
                'role' => 'student'
            ]);

            // Seleccionar perfil dominante aleatorio
            $profileKeys = array_keys($profiles);
            $dominantProfile = $profileKeys[array_rand($profileKeys)];
            $profileWeights = $profiles[$dominantProfile];

            // Generar respuestas basadas en el perfil
            $scores = [];
            foreach ($questions as $question) {
                $category = $question->category;
                $weight = $profileWeights[$category] ?? 0.5;

                // Generar respuesta con variación aleatoria
                $baseScore = round($weight * 5);
                $variation = rand(-1, 1);
                $score = max(1, min(5, $baseScore + $variation));

                TestResponse::create([
                    'user_id' => $user->id,
                    'vocational_test_id' => $test->id,
                    'test_question_id' => $question->id,
                    'answer' => (string)$score,
                    'score' => $score,
                    'completed_at' => now()->subDays(rand(1, 30))
                ]);

                if (!isset($scores[$category])) {
                    $scores[$category] = 0;
                }
                $scores[$category] += $score;
            }

            // Generar carreras recomendadas
            $recommendedCareers = $this->getRecommendedCareers($scores);

            // Crear resultado del test
            TestResult::create([
                'user_id' => $user->id,
                'vocational_test_id' => $test->id,
                'scores' => $scores,
                'recommended_careers' => $recommendedCareers,
                'analysis' => "Perfil dominante: {$dominantProfile}",
                'total_score' => array_sum($scores),
                'completed_at' => now()->subDays(rand(1, 30))
            ]);

            if ($i % 50 === 0) {
                $this->command->info("Generados {$i} estudiantes...");
            }
        }

        $this->command->info('✓ 500 estudiantes con respuestas generados exitosamente!');
    }

    private function getRecommendedCareers($scores)
    {
        $careerMap = [
            'realista' => ['Ingeniería Civil', 'Ingeniería Mecánica', 'Arquitectura'],
            'investigador' => ['Medicina', 'Ingeniería de Sistemas', 'Biología'],
            'artistico' => ['Diseño Gráfico', 'Arquitectura', 'Comunicación Audiovisual'],
            'social' => ['Psicología', 'Educación', 'Trabajo Social'],
            'emprendedor' => ['Administración de Empresas', 'Marketing', 'Derecho'],
            'convencional' => ['Contabilidad', 'Administración', 'Ingeniería Industrial'],
        ];

        arsort($scores);
        $topCategory = array_key_first($scores);

        return [[
            'category' => $topCategory,
            'careers' => $careerMap[$topCategory] ?? []
        ]];
    }
}

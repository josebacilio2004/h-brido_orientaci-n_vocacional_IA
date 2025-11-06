<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TestInterest;
use App\Models\InterestQuestion;

class TestInterestSeeder extends Seeder
{
    public function run(): void
    {
        $test = TestInterest::create([
            'name' => 'Test de Intereses',
            'description' => 'Identifica qué actividades y áreas del conocimiento te motivan más. Descubre tus preferencias vocacionales.',
            'total_questions' => 40,
            'duration_minutes' => 20,
            'is_active' => true,
        ]);

        // Definir categorías de intereses
        $questions = [
            ['question' => 'Me interesa diseñar y crear cosas nuevas', 'category' => 'Artístico'],
            ['question' => 'Disfruto ayudando a otras personas', 'category' => 'Social'],
            ['question' => 'Me fascinan los números y los cálculos', 'category' => 'Analítico'],
            ['question' => 'Prefiero trabajar con máquinas y herramientas', 'category' => 'Práctico'],
            ['question' => 'Me interesa emprender y crear mi propio negocio', 'category' => 'Empresarial'],
            ['question' => 'Disfruto investigar y resolver problemas complejos', 'category' => 'Investigativo'],
            ['question' => 'Me atrae la comunicación y expresión creativa', 'category' => 'Artístico'],
            ['question' => 'Me gusta organizar y planificar actividades', 'category' => 'Organizacional'],
            ['question' => 'Prefiero trabajar en equipo colaborando con otros', 'category' => 'Social'],
            ['question' => 'Me interesa la biología y ciencias naturales', 'category' => 'Científico'],
            ['question' => 'Disfruto enseñar y transmitir conocimiento', 'category' => 'Educativo'],
            ['question' => 'Me fascina la tecnología y la programación', 'category' => 'Tecnológico'],
            ['question' => 'Prefiero trabajos prácticos y de acción', 'category' => 'Práctico'],
            ['question' => 'Me interesa la psicología y comportamiento humano', 'category' => 'Social'],
            ['question' => 'Disfruto de trabajos analíticos y detallistas', 'category' => 'Analítico'],
            ['question' => 'Me atrae el arte y la creatividad visual', 'category' => 'Artístico'],
            ['question' => 'Me interesa la administración y gestión de empresas', 'category' => 'Empresarial'],
            ['question' => 'Prefiero investigar y experimentar constantemente', 'category' => 'Investigativo'],
            ['question' => 'Me gusta liderar y tomar decisiones', 'category' => 'Empresarial'],
            ['question' => 'Disfruto trabajar con sistemas y procesos', 'category' => 'Organizacional'],
            ['question' => 'Me interesa la medicina y salud', 'category' => 'Social'],
            ['question' => 'Prefiero trabajos creativos e innovadores', 'category' => 'Artístico'],
            ['question' => 'Me fascina la física y las matemáticas avanzadas', 'category' => 'Científico'],
            ['question' => 'Me gusta resolver conflictos y mediar', 'category' => 'Social'],
            ['question' => 'Me interesa la economía y finanzas', 'category' => 'Analítico'],
            ['question' => 'Prefiero trabajos de construcción y manufactura', 'category' => 'Práctico'],
            ['question' => 'Me atrae el derecho y la justicia', 'category' => 'Empresarial'],
            ['question' => 'Disfruto explorando nuevas ideas y conceptos', 'category' => 'Investigativo'],
            ['question' => 'Me interesa la sustentabilidad ambiental', 'category' => 'Científico'],
            ['question' => 'Prefiero trabajos que requieran precisión', 'category' => 'Analítico'],
            ['question' => 'Me gusta la música y artes performativas', 'category' => 'Artístico'],
            ['question' => 'Me interesa el marketing y la publicidad', 'category' => 'Empresarial'],
            ['question' => 'Prefiero trabajar independientemente', 'category' => 'Investigativo'],
            ['question' => 'Me atrae la diplomacia y relaciones internacionales', 'category' => 'Social'],
            ['question' => 'Disfruto reparar y mantener equipos', 'category' => 'Práctico'],
            ['question' => 'Me interesa la política y gestión pública', 'category' => 'Organizacional'],
            ['question' => 'Prefiero actividades que generen impacto social', 'category' => 'Social'],
            ['question' => 'Me fascina la astronología y ciencias del espacio', 'category' => 'Científico'],
            ['question' => 'Me gusta crear contenido digital y multimedia', 'category' => 'Tecnológico'],
            ['question' => 'Me interesa trabajar con datos y estadística', 'category' => 'Analítico'],
        ];

        foreach ($questions as $index => $data) {
            InterestQuestion::create([
                'test_interest_id' => $test->id,
                'question_number' => $index + 1,
                'question' => $data['question'],
                'category' => $data['category'],
                'options' => json_encode(['No me interesa', 'Poco interés', 'Interesado', 'Muy interesado', 'Extremadamente interesado']),
                'display_order' => $index + 1,
            ]);
        }
    }
}

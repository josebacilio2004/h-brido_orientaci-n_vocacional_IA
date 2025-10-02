<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VocationalTest;
use App\Models\TestQuestion;

class VocationalTestSeeder extends Seeder
{
    public function run()
    {
        // Crear el test principal basado en el método RIASEC de Holland
        $test = VocationalTest::create([
            'title' => 'Test Vocacional RIASEC',
            'description' => 'Test basado en la teoría de Holland que identifica tu tipo de personalidad vocacional y las carreras más compatibles con tus intereses.',
            'type' => 'riasec',
            'duration_minutes' => 20,
            'total_questions' => 60,
            'is_active' => true,
            'instructions' => 'Responde con sinceridad a cada pregunta según tu nivel de interés o acuerdo. No hay respuestas correctas o incorrectas.'
        ]);

        // Preguntas para cada categoría RIASEC
        $questions = [
            // REALISTA (R) - Práctico, técnico, mecánico
            [
                'category' => 'realista',
                'questions' => [
                    '¿Te gusta trabajar con herramientas y maquinaria?',
                    '¿Disfrutas de actividades al aire libre?',
                    '¿Te interesa reparar objetos o dispositivos?',
                    '¿Prefieres trabajar con tus manos?',
                    '¿Te gustan las actividades físicas y deportivas?',
                    '¿Te interesa la construcción o carpintería?',
                    '¿Disfrutas trabajando con animales?',
                    '¿Te gusta la agricultura o jardinería?',
                    '¿Prefieres tareas prácticas a teóricas?',
                    '¿Te interesa la mecánica automotriz?'
                ]
            ],
            // INVESTIGADOR (I) - Analítico, intelectual, científico
            [
                'category' => 'investigador',
                'questions' => [
                    '¿Te gusta resolver problemas complejos?',
                    '¿Disfrutas de las matemáticas y ciencias?',
                    '¿Te interesa investigar y descubrir cosas nuevas?',
                    '¿Prefieres trabajar de forma independiente?',
                    '¿Te gusta analizar datos e información?',
                    '¿Te interesa la tecnología y la innovación?',
                    '¿Disfrutas leyendo sobre temas científicos?',
                    '¿Te gusta hacer experimentos?',
                    '¿Prefieres entender el "por qué" de las cosas?',
                    '¿Te interesa la investigación médica o biológica?'
                ]
            ],
            // ARTÍSTICO (A) - Creativo, expresivo, original
            [
                'category' => 'artistico',
                'questions' => [
                    '¿Te gusta dibujar, pintar o diseñar?',
                    '¿Disfrutas de la música o tocar instrumentos?',
                    '¿Te interesa la escritura creativa?',
                    '¿Prefieres ambientes de trabajo flexibles?',
                    '¿Te gusta expresar tus ideas de forma original?',
                    '¿Te interesa el diseño de moda o interiores?',
                    '¿Disfrutas de la fotografía o video?',
                    '¿Te gusta el teatro o la actuación?',
                    '¿Prefieres proyectos creativos a rutinarios?',
                    '¿Te interesa la arquitectura o el arte visual?'
                ]
            ],
            // SOCIAL (S) - Ayudar, enseñar, cuidar
            [
                'category' => 'social',
                'questions' => [
                    '¿Te gusta ayudar a otras personas?',
                    '¿Disfrutas enseñando o explicando cosas?',
                    '¿Te interesa el trabajo comunitario?',
                    '¿Prefieres trabajar en equipo?',
                    '¿Te gusta escuchar y aconsejar a otros?',
                    '¿Te interesa la psicología o el comportamiento humano?',
                    '¿Disfrutas organizando eventos sociales?',
                    '¿Te gusta cuidar de niños o personas mayores?',
                    '¿Prefieres profesiones que ayuden a la sociedad?',
                    '¿Te interesa la educación o la salud?'
                ]
            ],
            // EMPRENDEDOR (E) - Persuasivo, líder, ambicioso
            [
                'category' => 'emprendedor',
                'questions' => [
                    '¿Te gusta liderar grupos o proyectos?',
                    '¿Disfrutas de los negocios y las ventas?',
                    '¿Te interesa persuadir o convencer a otros?',
                    '¿Prefieres tomar decisiones importantes?',
                    '¿Te gusta competir y ganar?',
                    '¿Te interesa el marketing o la publicidad?',
                    '¿Disfrutas negociando acuerdos?',
                    '¿Te gusta asumir riesgos calculados?',
                    '¿Prefieres ser tu propio jefe?',
                    '¿Te interesa la política o el derecho?'
                ]
            ],
            // CONVENCIONAL (C) - Organizado, detallista, metódico
            [
                'category' => 'convencional',
                'questions' => [
                    '¿Te gusta organizar y planificar?',
                    '¿Disfrutas trabajando con números y datos?',
                    '¿Te interesa seguir procedimientos establecidos?',
                    '¿Prefieres ambientes de trabajo estructurados?',
                    '¿Te gusta mantener registros ordenados?',
                    '¿Te interesa la contabilidad o finanzas?',
                    '¿Disfrutas de tareas administrativas?',
                    '¿Te gusta la precisión y el detalle?',
                    '¿Prefieres trabajar con sistemas y procesos?',
                    '¿Te interesa la gestión de información?'
                ]
            ]


        ];
        

        $questionOrder = 1;
        foreach ($questions as $categoryData) {
            foreach ($categoryData['questions'] as $questionText) {
                TestQuestion::create([
                    'vocational_test_id' => $test->id,
                    'question' => $questionText,
                    'type' => 'scale',
                    'category' => $categoryData['category'],
                    'options' => json_encode([
                        1 => 'Nada de acuerdo',
                        2 => 'Poco de acuerdo',
                        3 => 'Neutral',
                        4 => 'De acuerdo',
                        5 => 'Muy de acuerdo'
                    ]),
                    'question_number' => $questionOrder,
                    'order' => $questionOrder,
                ]);
                $questionOrder++;
            }
        }
    }
}

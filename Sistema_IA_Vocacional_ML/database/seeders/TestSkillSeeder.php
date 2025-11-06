<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TestSkill;
use App\Models\SkillQuestion;

class TestSkillSeeder extends Seeder
{
    public function run(): void
    {
        $test = TestSkill::create([
            'name' => 'Test de Habilidades',
            'description' => 'Evalúa tus fortalezas y competencias naturales. Descubre en qué áreas tienes mayor potencial.',
            'total_questions' => 50,
            'duration_minutes' => 25,
            'is_active' => true,
        ]);

        $questions = [
            ['question' => 'Soy bueno resolviendo problemas matemáticos', 'skill_category' => 'Matemática'],
            ['question' => 'Tengo excelente expresión oral y escrita', 'skill_category' => 'Comunicación'],
            ['question' => 'Soy creativo generando nuevas ideas', 'skill_category' => 'Creatividad'],
            ['question' => 'Tengo destreza manual y habilidad con herramientas', 'skill_category' => 'Práctico'],
            ['question' => 'Soy analítico y orientado al detalle', 'skill_category' => 'Análisis'],
            ['question' => 'Tengo liderazgo natural', 'skill_category' => 'Liderazgo'],
            ['question' => 'Soy empático y comprensivo con otros', 'skill_category' => 'Interpersonal'],
            ['question' => 'Tengo habilidad para programar y programas', 'skill_category' => 'Tecnología'],
            ['question' => 'Soy organizado y bueno en planificación', 'skill_category' => 'Organización'],
            ['question' => 'Tengo facilidad para aprender idiomas', 'skill_category' => 'Idiomas'],
            ['question' => 'Soy bueno en diseño gráfico y visual', 'skill_category' => 'Diseño'],
            ['question' => 'Tengo capacidad de negociación', 'skill_category' => 'Negociación'],
            ['question' => 'Soy investigador por naturaleza', 'skill_category' => 'Investigación'],
            ['question' => 'Tengo capacidad para trabajar bajo presión', 'skill_category' => 'Resilencia'],
            ['question' => 'Soy excelente atleta y coordina bien', 'skill_category' => 'Coordinación'],
            ['question' => 'Tengo facilidad para enseñar a otros', 'skill_category' => 'Enseñanza'],
            ['question' => 'Soy bueno en estadística y análisis de datos', 'skill_category' => 'Datos'],
            ['question' => 'Tengo habilidad artística y musical', 'skill_category' => 'Artística'],
            ['question' => 'Soy buen escritor y redactor', 'skill_category' => 'Escritura'],
            ['question' => 'Tengo capacidad para resolver conflictos', 'skill_category' => 'Mediación'],
            ['question' => 'Soy bueno en estrategia y planificación táctica', 'skill_category' => 'Estrategia'],
            ['question' => 'Tengo facilidad para memorizar información', 'skill_category' => 'Memoria'],
            ['question' => 'Soy creativo en soluciones innovadoras', 'skill_category' => 'Innovación'],
            ['question' => 'Tengo buen sentido del humor e ingenio', 'skill_category' => 'Humor'],
            ['question' => 'Soy disciplinado y perseverante', 'skill_category' => 'Disciplina'],
            ['question' => 'Tengo capacidad de visión de largo plazo', 'skill_category' => 'Visión'],
            ['question' => 'Soy adaptable a cambios rápidos', 'skill_category' => 'Adaptabilidad'],
            ['question' => 'Tengo habilidad en fotografía y videografía', 'skill_category' => 'Audiovisual'],
            ['question' => 'Soy bueno en networking y relaciones', 'skill_category' => 'Networking'],
            ['question' => 'Tengo capacidad de escucha activa', 'skill_category' => 'Escucha'],
            ['question' => 'Soy bueno en marketing y promoción', 'skill_category' => 'Marketing'],
            ['question' => 'Tengo facilidad para trabajar en equipo', 'skill_category' => 'Trabajo en equipo'],
            ['question' => 'Soy bueno reconociendo patrones', 'skill_category' => 'Reconocimiento de patrones'],
            ['question' => 'Tengo habilidad en cocina y gastronomía', 'skill_category' => 'Gastronomía'],
            ['question' => 'Soy bueno en deportes y actividades físicas', 'skill_category' => 'Actividad física'],
            ['question' => 'Tengo capacidad de improvisación', 'skill_category' => 'Improvisación'],
            ['question' => 'Soy ordenado y meticuloso', 'skill_category' => 'Orden'],
            ['question' => 'Tengo buena retención visual', 'skill_category' => 'Visualización'],
            ['question' => 'Soy bueno en entretenimiento y animación', 'skill_category' => 'Entretenimiento'],
            ['question' => 'Tengo habilidad para diagnosticar problemas', 'skill_category' => 'Diagnóstico'],
            ['question' => 'Soy bueno en construcción y modelado', 'skill_category' => 'Construcción'],
            ['question' => 'Tengo capacidad de síntesis y resumen', 'skill_category' => 'Síntesis'],
            ['question' => 'Soy bueno en debate y argumentación', 'skill_category' => 'Debate'],
            ['question' => 'Tengo habilidad en manualidades y artesanía', 'skill_category' => 'Artesanía'],
            ['question' => 'Soy bueno en orientación espacial', 'skill_category' => 'Espacial'],
            ['question' => 'Tengo facilidad para predicción y anticipación', 'skill_category' => 'Anticipación'],
            ['question' => 'Soy bueno en gestión de proyectos', 'skill_category' => 'Gestión'],
            ['question' => 'Tengo capacidad de pensamiento crítico', 'skill_category' => 'Pensamiento crítico'],
            ['question' => 'Soy bueno en gestión de recursos', 'skill_category' => 'Recursos'],
            ['question' => 'Tengo habilidad en educación ambiental', 'skill_category' => 'Ambiental'],
        ];

        foreach ($questions as $index => $data) {
            SkillQuestion::create([
                'test_skill_id' => $test->id,
                'question_number' => $index + 1,
                'question' => $data['question'],
                'skill_category' => $data['skill_category'],
                'options' => json_encode(['Totalmente en desacuerdo', 'En desacuerdo', 'Neutral', 'De acuerdo', 'Totalmente de acuerdo']),
                'display_order' => $index + 1,
            ]);
        }
    }
}

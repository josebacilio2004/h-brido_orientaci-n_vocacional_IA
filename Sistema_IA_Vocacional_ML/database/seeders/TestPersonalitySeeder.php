<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TestPersonality;
use App\Models\PersonalityQuestion;

class TestPersonalitySeeder extends Seeder
{
    public function run(): void
    {
        $test = TestPersonality::create([
            'name' => 'Test de Personalidad',
            'description' => 'Conoce tu tipo de personalidad y cómo influye en tu carrera. Entiende tus rasgos y preferencias.',
            'total_questions' => 40,
            'duration_minutes' => 20,
            'is_active' => true,
        ]);

        $questions = [
            ['question' => 'Prefiero pasar tiempo en grupo que solo', 'trait' => 'Introvertido/Extrovertido'],
            ['question' => 'Confío más en la intuición que en datos concretos', 'trait' => 'Intuición/Sensación'],
            ['question' => 'En decisiones, uso la lógica más que los sentimientos', 'trait' => 'Pensamiento/Sentimiento'],
            ['question' => 'Prefiero estructura y orden antes que flexibilidad', 'trait' => 'Juzgar/Percibir'],
            ['question' => 'Soy el alma de la fiesta', 'trait' => 'Introvertido/Extrovertido'],
            ['question' => 'Veo posibilidades en lugares donde otros ven solo hechos', 'trait' => 'Intuición/Sensación'],
            ['question' => 'Me enfoco en el bienestar de las personas', 'trait' => 'Pensamiento/Sentimiento'],
            ['question' => 'Necesito que todo esté planificado antes de actuar', 'trait' => 'Juzgar/Percibir'],
            ['question' => 'Me canso fácilmente en eventos sociales prolongados', 'trait' => 'Introvertido/Extrovertido'],
            ['question' => 'Prefiero información práctica y realista', 'trait' => 'Intuición/Sensación'],
            ['question' => 'Tomo decisiones basadas en principios y valores', 'trait' => 'Pensamiento/Sentimiento'],
            ['question' => 'Me adapto bien a cambios inesperados', 'trait' => 'Juzgar/Percibir'],
            ['question' => 'Hablo primero, pienso después', 'trait' => 'Introvertido/Extrovertido'],
            ['question' => 'Me entusiasman las nuevas ideas y proyectos', 'trait' => 'Intuición/Sensación'],
            ['question' => 'Considero cómo mis decisiones afectarán a otros', 'trait' => 'Pensamiento/Sentimiento'],
            ['question' => 'Dejo tareas incompletas hasta el último momento', 'trait' => 'Juzgar/Percibir'],
            ['question' => 'Busco entusiasmo y energía en mi entorno', 'trait' => 'Introvertido/Extrovertido'],
            ['question' => 'Prefiero la experiencia directa al análisis teórico', 'trait' => 'Intuición/Sensación'],
            ['question' => 'Me preocupa dañar sentimientos de otros', 'trait' => 'Pensamiento/Sentimiento'],
            ['question' => 'Sigo mi propia agenda sin horarios fijos', 'trait' => 'Juzgar/Percibir'],
            ['question' => 'Me agrada tener muchos conocidos', 'trait' => 'Introvertido/Extrovertido'],
            ['question' => 'Enfocado en el futuro más que en el presente', 'trait' => 'Intuición/Sensación'],
            ['question' => 'Más importante es ser justo que ser empático', 'trait' => 'Pensamiento/Sentimiento'],
            ['question' => 'Tengo una lista de cosas por hacer bien organizada', 'trait' => 'Juzgar/Percibir'],
            ['question' => 'Prefiero conversaciones profundas con pocos amigos', 'trait' => 'Introvertido/Extrovertido'],
            ['question' => 'Veo conexiones entre ideas dispares', 'trait' => 'Intuición/Sensación'],
            ['question' => 'Soy genuino en mis relaciones interpersonales', 'trait' => 'Pensamiento/Sentimiento'],
            ['question' => 'Prefiero resultados predecibles', 'trait' => 'Juzgar/Percibir'],
            ['question' => 'Me considero sociable y comunicativo', 'trait' => 'Introvertido/Extrovertido'],
            ['question' => 'Disfruto del detalle en la ejecución', 'trait' => 'Intuición/Sensación'],
            ['question' => 'Critico duramente ideas defectuosas', 'trait' => 'Pensamiento/Sentimiento'],
            ['question' => 'Mi escritorio está desorganizado pero productivo', 'trait' => 'Juzgar/Percibir'],
            ['question' => 'Necesito tiempo solo para recargarme', 'trait' => 'Introvertido/Extrovertido'],
            ['question' => 'Confío en patrones que hayan funcionado antes', 'trait' => 'Intuición/Sensación'],
            ['question' => 'Los valores personales guían mis decisiones', 'trait' => 'Pensamiento/Sentimiento'],
            ['question' => 'Disfruto de la rutina y previsibilidad', 'trait' => 'Juzgar/Percibir'],
            ['question' => 'Disfruto ser el centro de atención', 'trait' => 'Introvertido/Extrovertido'],
            ['question' => 'Busco significado más allá de lo obvio', 'trait' => 'Intuición/Sensación'],
            ['question' => 'Armonía en relaciones es muy importante para mí', 'trait' => 'Pensamiento/Sentimiento'],
            ['question' => 'Prefiero mantener opciones abiertas', 'trait' => 'Juzgar/Percibir'],
        ];

        foreach ($questions as $index => $data) {
            PersonalityQuestion::create([
                'test_personality_id' => $test->id,
                'question_number' => $index + 1,
                'question' => $data['question'],
                'trait' => $data['trait'],
                'options' => json_encode(['Muy en desacuerdo', 'En desacuerdo', 'Neutral', 'De acuerdo', 'Muy de acuerdo']),
                'display_order' => $index + 1,
            ]);
        }
    }
}

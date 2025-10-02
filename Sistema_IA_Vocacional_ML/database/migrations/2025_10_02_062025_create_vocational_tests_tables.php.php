<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla de tests vocacionales
        Schema::create('vocational_tests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('type'); // 'interests', 'skills', 'personality', 'academic', 'riasec'
            $table->integer('duration_minutes')->default(30);
            $table->integer('total_questions')->default(0);
            $table->text('instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabla de preguntas
        Schema::create('test_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vocational_test_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->string('type'); // 'multiple_choice', 'scale', 'yes_no'
            $table->json('options')->nullable();
            $table->string('category')->nullable();
            $table->integer('question_number')->default(1); // 👈 agregado
            $table->integer('order')->default(1);
            $table->timestamps();
        });

        // Tabla de respuestas de usuarios
        Schema::create('test_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vocational_test_id')->constrained()->onDelete('cascade');
            $table->foreignId('test_question_id')->constrained()->onDelete('cascade');
            $table->text('answer');
            $table->integer('score')->nullable();
            $table->timestamp('completed_at')->nullable(); // 👈 agregado para evitar error
            $table->timestamps();
        });

        // Tabla de resultados de tests
        Schema::create('test_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vocational_test_id')->constrained()->onDelete('cascade');
            $table->json('scores'); // Puntajes por categoría
            $table->json('recommended_careers'); // Carreras recomendadas por IA
            $table->text('analysis')->nullable(); // Análisis detallado
            $table->integer('total_score')->default(0);
            $table->timestamp('completed_at'); // fecha en que se completó el test
            $table->timestamps();
        });

        // Tabla de carreras
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description');
            $table->string('category'); // 'ingenieria', 'salud', 'ciencias_sociales', etc.
            $table->json('required_skills'); // Habilidades requeridas
            $table->json('related_subjects'); // Materias relacionadas
            $table->decimal('average_salary', 10, 2)->nullable();
            $table->integer('duration_years')->default(5);
            $table->text('job_opportunities')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamps();
        });

        // Tabla de notas académicas del estudiante
        Schema::create('student_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('nota_matematica')->default(0);
            $table->integer('nota_comunicacion')->default(0);
            $table->integer('nota_ciencias_sociales')->default(0);
            $table->integer('nota_ciencia_tecnologia')->default(0);
            $table->integer('nota_desarrollo_personal')->default(0);
            $table->integer('nota_ciudadania_civica')->default(0);
            $table->integer('nota_educacion_fisica')->default(0);
            $table->integer('nota_ingles')->default(0);
            $table->integer('nota_educacion_trabajo')->default(0);
            $table->string('academic_year')->default('2024');
            $table->timestamps();
        });

        // Tabla de predicciones de IA
        Schema::create('ai_predictions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('input_data'); // Datos enviados al modelo
            $table->string('predicted_career');
            $table->decimal('confidence', 5, 2)->default(0); // Confianza del modelo
            $table->json('top_careers')->nullable(); // Top 3-5 carreras
            $table->string('model_version')->default('1.0');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_predictions');
        Schema::dropIfExists('student_grades');
        Schema::dropIfExists('careers');
        Schema::dropIfExists('test_results');
        Schema::dropIfExists('test_responses');
        Schema::dropIfExists('test_questions');
        Schema::dropIfExists('vocational_tests');
    }
};

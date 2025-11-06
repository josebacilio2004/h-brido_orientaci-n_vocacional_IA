<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabla: test_interests (Test de Intereses)
        Schema::create('test_interests', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Test de Intereses');
            $table->text('description')->nullable();
            $table->integer('total_questions')->default(40);
            $table->integer('duration_minutes')->default(20);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabla: test_skills (Test de Habilidades)
        Schema::create('test_skills', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Test de Habilidades');
            $table->text('description')->nullable();
            $table->integer('total_questions')->default(50);
            $table->integer('duration_minutes')->default(25);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabla: test_personality (Test de Personalidad)
        Schema::create('test_personality', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Test de Personalidad');
            $table->text('description')->nullable();
            $table->integer('total_questions')->default(40);
            $table->integer('duration_minutes')->default(20);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tabla: interest_questions (Preguntas del Test de Intereses)
        Schema::create('interest_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_interest_id')->constrained('test_interests')->onDelete('cascade');
            $table->integer('question_number');
            $table->text('question');
            $table->string('category'); // Área de interés (Científico, Artístico, etc)
            $table->json('options')->nullable();
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->foreign('test_interest_id')->references('id')->on('test_interests')->onDelete('cascade');
            $table->unique(['test_interest_id', 'question_number'], 'idx_int_quest_num');
        });

        // Tabla: skill_questions (Preguntas del Test de Habilidades)
        Schema::create('skill_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_skill_id')->constrained('test_skills')->onDelete('cascade');
            $table->integer('question_number');
            $table->text('question');
            $table->string('skill_category'); // Tipo de habilidad (Matemática, Comunicación, etc)
            $table->json('options')->nullable();
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->foreign('test_skill_id')->references('id')->on('test_skills')->onDelete('cascade');
            $table->unique(['test_skill_id', 'question_number'], 'idx_skill_quest_num');
        });

        // Tabla: personality_questions (Preguntas del Test de Personalidad)
        Schema::create('personality_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('test_personality_id')->constrained('test_personality')->onDelete('cascade');
            $table->integer('question_number');
            $table->text('question');
            $table->string('trait'); // Rasgo de personalidad (Introvertido, Extrovertido, etc)
            $table->json('options')->nullable();
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->foreign('test_personality_id')->references('id')->on('test_personality')->onDelete('cascade');
            $table->unique(['test_personality_id', 'question_number'], 'idx_pers_quest_num');
        });

        // Tabla: interest_responses (Respuestas del Test de Intereses)
        Schema::create('interest_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('test_interest_id');
            $table->unsignedBigInteger('interest_question_id');
            $table->text('answer')->nullable();
            $table->integer('score')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('test_interest_id')->references('id')->on('test_interests')->onDelete('cascade');
            $table->foreign('interest_question_id')->references('id')->on('interest_questions')->onDelete('cascade');
            $table->unique(['user_id', 'test_interest_id', 'interest_question_id'], 'idx_int_resp_unique');
        });

        // Tabla: skill_responses (Respuestas del Test de Habilidades)
        Schema::create('skill_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('test_skill_id');
            $table->unsignedBigInteger('skill_question_id');
            $table->text('answer')->nullable();
            $table->integer('score')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('test_skill_id')->references('id')->on('test_skills')->onDelete('cascade');
            $table->foreign('skill_question_id')->references('id')->on('skill_questions')->onDelete('cascade');
            $table->unique(['user_id', 'test_skill_id', 'skill_question_id'], 'idx_skill_resp_unique');
        });

        // Tabla: personality_responses (Respuestas del Test de Personalidad)
        Schema::create('personality_responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('test_personality_id');
            $table->unsignedBigInteger('personality_question_id');
            $table->text('answer')->nullable();
            $table->integer('score')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('test_personality_id')->references('id')->on('test_personality')->onDelete('cascade');
            $table->foreign('personality_question_id')->references('id')->on('personality_questions')->onDelete('cascade');
            $table->unique(['user_id', 'test_personality_id', 'personality_question_id'], 'idx_pers_resp_unique');
        });

        // Tabla: interest_results (Resultados del Test de Intereses)
        Schema::create('interest_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('test_interest_id');
            $table->json('scores')->nullable(); // {category: score}
            $table->json('recommended_careers')->nullable();
            $table->text('analysis')->nullable();
            $table->integer('total_score')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('test_interest_id')->references('id')->on('test_interests')->onDelete('cascade');
            $table->unique(['user_id', 'test_interest_id'], 'idx_int_results_unique');
        });

        // Tabla: skill_results (Resultados del Test de Habilidades)
        Schema::create('skill_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('test_skill_id');
            $table->json('scores')->nullable(); // {category: score}
            $table->json('recommended_careers')->nullable();
            $table->text('analysis')->nullable();
            $table->integer('total_score')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('test_skill_id')->references('id')->on('test_skills')->onDelete('cascade');
            $table->unique(['user_id', 'test_skill_id'], 'idx_skill_results_unique');
        });

        // Tabla: personality_results (Resultados del Test de Personalidad)
        Schema::create('personality_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('test_personality_id');
            $table->json('scores')->nullable(); // {trait: score}
            $table->json('recommended_careers')->nullable();
            $table->text('analysis')->nullable();
            $table->integer('total_score')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('test_personality_id')->references('id')->on('test_personality')->onDelete('cascade');
            $table->unique(['user_id', 'test_personality_id'], 'idx_pers_results_unique');
        });

        // Tabla: ml_predictions (Predicciones del modelo ML)
        Schema::create('ml_predictions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('prediction_type'); // riasec, interests, skills, personality, combined
            $table->json('features')->nullable(); // Features usadas para predicción
            $table->json('predicted_careers')->nullable(); // Carreras predichas con probabilidades
            $table->float('confidence_score')->default(0); // Confianza de la predicción
            $table->json('model_metadata')->nullable(); // Metadatos del modelo
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->index('prediction_type', 'idx_pred_type');
            $table->index(['user_id', 'prediction_type'], 'idx_user_pred_type');
        });

        // Tabla: model_performance (Métricas de rendimiento del modelo)
        Schema::create('model_performance', function (Blueprint $table) {
            $table->id();
            $table->string('model_name');
            $table->float('accuracy')->nullable();
            $table->float('precision')->nullable();
            $table->float('recall')->nullable();
            $table->float('f1_score')->nullable();
            $table->json('confusion_matrix')->nullable();
            $table->timestamp('trained_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('model_name', 'idx_model_name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_performance');
        Schema::dropIfExists('ml_predictions');
        Schema::dropIfExists('personality_results');
        Schema::dropIfExists('skill_results');
        Schema::dropIfExists('interest_results');
        Schema::dropIfExists('personality_responses');
        Schema::dropIfExists('skill_responses');
        Schema::dropIfExists('interest_responses');
        Schema::dropIfExists('personality_questions');
        Schema::dropIfExists('skill_questions');
        Schema::dropIfExists('interest_questions');
        Schema::dropIfExists('test_personality');
        Schema::dropIfExists('test_skills');
        Schema::dropIfExists('test_interests');
    }
};
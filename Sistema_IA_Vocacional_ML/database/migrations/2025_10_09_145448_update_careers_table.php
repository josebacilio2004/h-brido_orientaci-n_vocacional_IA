<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->string('faculty')->after('category'); // Facultad
            $table->string('university')->default('Universidad Continental')->after('faculty');
            $table->string('campus')->default('Huancayo')->after('university');
            $table->string('riasec_profile')->nullable()->after('campus'); // Perfil RIASEC dominante
            $table->json('riasec_scores')->nullable()->after('riasec_profile'); // Puntajes RIASEC
        });
    }

    public function down(): void
    {
        Schema::table('careers', function (Blueprint $table) {
            $table->dropColumn(['faculty', 'university', 'campus', 'riasec_profile', 'riasec_scores']);
        });
    }
};

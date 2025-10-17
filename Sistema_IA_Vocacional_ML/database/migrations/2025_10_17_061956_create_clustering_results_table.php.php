<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clustering_results', function (Blueprint $table) {
            $table->id();
            $table->integer('cluster_id')->unique();
            $table->json('user_ids')->comment('IDs de usuarios en el cluster');
            $table->json('cluster_data')->comment('Datos del cluster (centroide, etc)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clustering_results');
    }
};

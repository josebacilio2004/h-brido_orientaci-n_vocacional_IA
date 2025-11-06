<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('grade')->nullable();
            $table->string('school')->nullable();
            $table->json('test_results')->nullable();
            $table->json('career_preferences')->nullable();
            $table->timestamp('last_test_date')->nullable();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['grade', 'school', 'test_results', 'career_preferences', 'last_test_date']);
        });
    }
};

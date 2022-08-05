<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('role_project_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_subject_area_id')->nullable()->constrained('role_subject_areas')->onDelete('cascade');
            $table->foreignId('project_team_id')->nullable()->constrained('project_teams')->onDelete('cascade');
            $table->foreignId('student_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('role_project_teams');
    }
};

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
        Schema::create('project_subject_areas', function (Blueprint $table) {
            $table->primary(['project_id', 'subject_area_id']);
            $table->foreignId('project_id')->constrained('projects')->onDelete('cascade');
            $table->foreignId('subject_area_id')->constrained('subject_areas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_subject_areas');
    }
};

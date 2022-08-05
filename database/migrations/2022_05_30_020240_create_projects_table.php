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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('project_type_id')->constrained('project_types')->onDelete('cascade');
            $table->string('name');
            $table->text('description');
            $table->date('start_date');
            $table->date('finish_date');
            $table->text('remuneration');
            $table->foreignId('moderator_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->integer('selected_role_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
};

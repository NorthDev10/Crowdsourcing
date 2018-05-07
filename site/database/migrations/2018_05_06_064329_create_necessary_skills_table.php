<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNecessarySkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('necessary_skills', function (Blueprint $table) {
            $table->integer('project_id')->unsigned();
            $table->integer('skill_id')->unsigned();

            $table->unique(['project_id', 'skill_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('necessary_skills');
    }
}

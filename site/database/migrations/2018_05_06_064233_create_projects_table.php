<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('type_project_id')->unsigned();
            $table->integer('business_activity_id')->unsigned();
            $table->string('brand', 50);
            $table->string('project_name', 255);
            $table->text('project_description');
            $table->string('slug', 512)->unique();
            $table->enum('status', ['opened', 'performed', 'closed'])
                  ->default('opened');
            $table->tinyInteger('tender_closing')->unsigned()->default(6);
            $table->dateTime('deadline');
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
}

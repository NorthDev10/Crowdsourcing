<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaskExecutorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('task_executors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subtask_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->text('comment')->nullable();
            $table->boolean('user_selected')->default(0);
            $table->timestamps();

            $table->unique(['subtask_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_executors');
    }
}

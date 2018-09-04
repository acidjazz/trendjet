<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


class CreateSessionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {

    Schema::create('sessions', function (Blueprint $table) {
      $table->string('token', 64)->unique();
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
      $table->string('source')->nullable();
      $table->string('cookie', 64);
      $table->boolean('verified')->default(false);

      // user metadata
      $table->string('to')->nullable();
      $table->string('ip', 15)->nullable();
      $table->string('agent')->nullable();

      $table->string('location')->nullable();

      $table->timestamps();
      $table->primary('token');
    });

  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('sessions');
  }

}

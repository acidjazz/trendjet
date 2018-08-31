<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
public function up()
{
  Schema::create('users', function (Blueprint $table) {
    $table->increments('id');
    $table->string('name');
    $table->string('email')->unique();
    $table->string('avatar')->nullable();
    $table->timestamps();
  });

  Schema::create('providers', function (Blueprint $table) {
    $table->increments('id');
    $table->integer('user_id')->unsigned();
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    $table->string('avatar')->nullable();
    $table->string('name');
    $table->enum('type', ['facebook','google']);
    $table->text('payload');
    $table->timestamps();
  });

  Schema::create('sessions', function (Blueprint $table) {

    $table->string('token', 64)->unique();
    $table->integer('user_id')->unsigned();
    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    $table->string('source')->default('attempt');
    $table->string('cookie', 64);
    $table->boolean('verified')->default(false);

    // user metadata
    $table->string('to')->nullable();
    $table->string('ip', 15)->nullable();
    $table->string('agent')->nullable();

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
    Schema::dropIfExists('users');
    Schema::dropIfExists('providers');
    Schema::dropIfExists('sessions');
}

}

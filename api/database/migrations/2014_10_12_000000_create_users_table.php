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
    $table->string('role')->default('member');
    $table->integer('views')->default(0);
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

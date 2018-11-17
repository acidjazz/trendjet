<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViewsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /**
         * Videos from youtube
         */
        Schema::create('videos', function (Blueprint $table) {
            $table->string('id');
            $table->primary('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('title');
            $table->integer('views');
            $table->string('duration');
            $table->timestamps();
        });

        /**
         * Logs table of views history
         */
        Schema::create('video_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('video_id');
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');
            $table->integer('views');
            $table->datetime('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

        /**
         * Varioius packages with view count and price
         */
        Schema::create('packages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->decimal('price', 8, 2);
            $table->integer('views');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

       /**
         * Package purchases
         */
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('package_id')->unsigned();
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('views');
            $table->timestamps();
        });

        /**
         * Boosts, delivering views to videos
         */
        Schema::create('boosts', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->string('video_id');
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');

            $table->string('status');

            $table->integer('views');
            $table->integer('delivered');
            $table->integer('remaining')->storedAs('views - delivered');

            $table->timestamps();
        });


        /**
         * Shots, screenshots of views
         */
        Schema::create('shots', function (Blueprint $table) {
            $table->increments('id');

            $table->string('video_id');
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');

            $table->integer('boost_id')->unsigned();
            $table->foreign('boost_id')->references('id')->on('boosts')->onDelete('cascade');

            $table->string('file');
            $table->string('duration');
            $table->timestamps();
        });

        /**
         * Views user has purchased and left over
         */
        Schema::create('activities', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('action');
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
        Schema::dropIfExists('videos');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('purchases');
        Schema::dropIfExists('boosts');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecycledPostImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recycled_post_images', function (Blueprint $table) {
            $table->id();
            $table->string('img_path')->nullable();
            $table->unsignedBigInteger('recycled_post_id');
            $table->foreign('recycled_post_id')->references('id')->on('recycled_posts');
            $table->index('recycled_post_id');
            $table->boolean('publishable')->default(1);
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
        Schema::dropIfExists('recycled_post_images');
    }
}

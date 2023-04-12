<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecycledPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recycled_posts', function (Blueprint $table) {
            $table->id(); 
            $table->string('type')->nullable();
            $table->index('type'); 
            $table->string('category')->nullable(); 
            $table->index('category'); 
            $table->string('country')->nullable();
            $table->string('province')->nullable();
            $table->string('city')->nullable();
            $table->string('address')->nullable();
            $table->string('title');
            $table->index('title');
            $table->string('description');
            $table->fulltext('description');
            $table->integer('price')->nullable();
            $table->index('price'); 
            //$table->point('position')/*->change()*/;
            //$table->spatialIndex('position');
            $table->integer('score')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->boolean('acceptable')->default(1);
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
        Schema::dropIfExists('recycled_posts');
    }
}

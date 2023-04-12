<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
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
            $table->boolean('acceptable')->default(0);
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
        Schema::table('posts', function(Blueprint $table){
            $table->dropForeign('posts_user_id_foreign');
            $table->dropColumn('user_id');
        });
        /*Schema::table('posts', function (Blueprint $table) {
            $table->dropSpatialIndex(['position']); // either an array of column names or the index name
        });*/
        Schema::dropIfExists('posts');
    }
}

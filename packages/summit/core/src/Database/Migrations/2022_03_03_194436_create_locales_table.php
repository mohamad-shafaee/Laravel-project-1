<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLocalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locales', function (Blueprint $table) {
            //$table->id();
            //$table->timestamps();
            $table->id();
            //$table->timestamps()->nullable();
            $table->timestamps();
            $table->string('code', 100);
            $table->string('name', 100);
            $table->enum('direction', ['ltr', 'rtl']);
            $table->text('locale-image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locales');
    }
}

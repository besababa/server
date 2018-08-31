<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_supplies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id')->unsigned();
            $table->integer('user_id')->unsigned()->nullable();
            $table->tinyInteger('type')->unsigned(); 
            $table->string('title');
            $table->integer('count')->unsigned();
            
            $table->index(['event_id','user_id']);
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
        Schema::dropIfExists('event_supplies');
    }
}

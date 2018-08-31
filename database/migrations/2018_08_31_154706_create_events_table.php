<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * _id:any;
    published: boolean;
    image: string;
    end_date:string;
    start_date:string;
    title: string;
    description:string;
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->boolean('published');
            $table->string('image', 100);
            $table->string('title', 100);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->text('description');
            $table->timestamps();
            $table->index(['title', 'start_date','end_date','published']);



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}

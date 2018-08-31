<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOnThisDay extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('on_this_day', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 100);
            $table->string('current_day', 4);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // Seeding table
        $path = storage_path() . '/files/onThisDay.csv';
        $data = [];
        if(file_exists($path)){
            $file = explode(PHP_EOL, file_get_contents($path));
            array_pop($file);
            foreach ($file as $key => $line) {
              $line = explode(',', $line);
              $data[$key]['current_day'] = $line[0];
              $data[$key]['title'] = $line[1];
              $data[$key]['created_at'] = \Carbon\Carbon::now();
              $data[$key]['updated_at'] = \Carbon\Carbon::now();
            }

          DB::table('on_this_day')->insert($data);

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('on_this_day');
    }
}

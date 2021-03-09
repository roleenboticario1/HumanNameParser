<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTravelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('travels', function (Blueprint $table) {
            $table->id();
            $table->integer('profile_id');
            $table->integer('passport_id');
            $table->string('activity');
            $table->string('port_of_entry');
            $table->string('port_of_exit');
            $table->text('purpose_of_visit');
            $table->string('length_of_stay');
            $table->date('date_of_visit');
            $table->string('airline');
            $table->integer('flight_no');
            $table->timestamps()
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('travel');
    }
}

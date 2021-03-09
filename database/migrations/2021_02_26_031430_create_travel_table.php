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
            $table->string('travels_activity');
            $table->string('travels_port_of_entry');
            $table->string('travels_port_of_exit');
            $table->text('travels_purpose_of_visit');
            $table->string('travels_length_of_stay');
            $table->date('travels_date_of_visit');
            $table->string('travels_airline');
            $table->string('travels_flight_no');
            $table->string('travels_notes');
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
        Schema::dropIfExists('travel');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->integer('profile_id');
            $table->string('profile_firstname');
            $table->string('profile_middlename');
            $table->string('profile_lastname');
            $table->string('profile_gender');
            $table->string('profile_address1');
            $table->string('profile_address2');
            $table->string('profile_address3');
            $table->string('profile_address4');
            $table->string('profile_subburd');
            $table->string('profile_state');
            $table->string('profile_country');
            $table->string('profile_postcode');
            $table->date('profile_birthdate');
            $table->string('profile_mobile_number');
            $table->string('profile_phone1');
            $table->string('profile_phone2');
            $table->string('profile_nationality');
            $table->string('profile_birthplace');
            $table->string('profile_person_of_ineterest');
            $table->string('profile_flags');
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
        Schema::dropIfExists('profiles');
    }
}

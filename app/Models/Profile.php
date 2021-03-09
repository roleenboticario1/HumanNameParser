<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $table = 'profiles'

    protected $fillable = [
           'profile_id', 'profile_firstname', 'profile_middlename', 'profile_lastname',  'profile_gender',  'profile_address1', 'profile_address2', 
           'profile_address3', 'profile_address4',  'profile_subburd',  'profile_state', 'profile_country', 'profile_postcode', 'profile_birthdate', 'profile_mobile_number', 'profile_phone1', 'profile_phone2', 'profile_nationality', 'profile_birthplace', 'profile_person_of_ineterest', 'profile_flags'
    ]  

	public function passport()
	{
		return $this->hasMany(Passport::class)
	} 

	public function travel()
	{
		return $this->hasMany(Travel::class)
	} 
}

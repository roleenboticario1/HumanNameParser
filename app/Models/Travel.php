<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Travel extends Model
{
    protected $table = 'travels';
   
    protected $fillable = [
        'address_of_stay','travels_activity','travels_port_of_entry', 'travels_port_of_exit', 'travels_purpose_of_visit', 'travels_length_of_stay','travels_date_of_visit','travels_airline','travels_flight_no','travels_notes','updated_by'
    ];


    // public function profile()
    // {
    // 	return $this->belongsTo(Profile::class);
    // }
}

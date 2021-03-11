<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Passport extends Model
{
    protected $table = 'passports';

    protected $fillable = [
       'passport_type','passport_country_code', 'passport_no', 'passport_issue_date', 'passport_expiration_date'
    ];

    
    // public function profile()
    // {
    // 	return $this->belongsTo(Profile::class);
    // }
}




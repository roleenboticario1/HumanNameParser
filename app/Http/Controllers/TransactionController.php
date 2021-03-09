<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Travel;
use App\Models\Passport;
use App\Models\Profile;
use Carbon\Carbon;
use DB;

class TransactionController extends Controller
{
	public function getAllTravelers(Request $request)
	{
	    $start    = Carbon::parse($request->date_from)->format('Y-m-d');
	    $end      = Carbon::parse($request->date_to)->format('Y-m-d');
	    $offset   = $request->input('offset');
	    $limit    = $request->input('limit');

	    if ($limit > 1000) {
	      return response()->json(["message" => "Request Failed."]);
	    }

	    $data = DB::table('travels')
	      ->whereDate('travels.created_at', '>=', $start)
	      ->WhereDate('travels.created_at', '<=', $end)
	      ->join('profiles', 'travels.profile_id', '=', 'profiles.id')
	      ->join('passports', 'travels.profile_id', '=', 'passports.id')
	      ->select('travels.*','profiles.*','passports.*')
	      ->orderBy('travels.created_at', 'DESC')
	      ->offset($offset)->limit($limit)->get();

	    return $data;

	}
	
    public function getTotalCountByGender(Request $request)
    {
       
        $start = Carbon::parse($request->date_from)->format('Y-m-d');
	    $end   = Carbon::parse($request->date_to)->format('Y-m-d');

        $total = Db::table('travels')
          ->whereDate('travels.travels_date_of_visit', '>=', $start)
          ->whereDate('travels.travels_date_of_visit', '<=', $end)
          ->join('profiles','travels.profile_id','=','profiles.id')
          ->select(('profiles.profile_gender AS GENDER'), ('travels.travels_activity AS ACTIVITY'), DB::raw('COUNT(profiles.profile_gender) AS COUNT'))
          ->groupBy('GENDER','ACTIVITY')
          ->get();

        $total_output = [];
        foreach ($total as $entry) {
             $total_output[$entry->ACTIVITY][$entry->GENDER] = $entry->COUNT;
        }

	    $daily = Db::table('travels')
	      ->whereDate('travels.travels_date_of_visit', '>=', $start)
          ->whereDate('travels.travels_date_of_visit', '<=', $end)
          ->join('profiles','travels.profile_id','=','profiles.id')
          ->select(DB::raw('DATE(travels.travels_date_of_visit) AS DATE'), ('profiles.profile_gender as GENDER'), DB::raw('COUNT(profiles.profile_gender) AS COUNT'))
          ->groupBy('DATE','GENDER')
          ->orderBy('travels.travels_date_of_visit', 'DESC')
          ->get();

	    $daily_output = [];
	    foreach ($daily as $entry) {
	         $daily_output[$entry->DATE][$entry->GENDER] = $entry->COUNT;
	    }

	    return response()->json(["activity" => $total_output , "daily"=> $daily_output ]);

	}


    public function getTotalCountByCountry(Request $request)
    {
       
        $start = Carbon::parse($request->date_from)->format('Y-m-d');
	    $end   = Carbon::parse($request->date_to)->format('Y-m-d');

        $total = Db::table('travels')
          ->whereDate('travels.travels_date_of_visit', '>=', $start)
          ->whereDate('travels.travels_date_of_visit', '<=', $end)
          ->join('profiles','travels.profile_id','=','profiles.id')
          ->select(('profiles.profile_country AS  COUNTRY'), ('travels.travels_activity AS ACTIVITY'), DB::raw('COUNT(profiles.profile_country) AS COUNT'))
          ->groupBy('COUNTRY','ACTIVITY')
          ->get();

         $total_output = [];
         foreach ($total as $entry) {
             $total_output[$entry->ACTIVITY][$entry->COUNTRY] = $entry->COUNT;
        }

	    $daily = Db::table('travels')
	      ->whereDate('travels.travels_date_of_visit', '>=', $start)
          ->whereDate('travels.travels_date_of_visit', '<=', $end)
          ->join('profiles','travels.profile_id','=','profiles.id')
          ->select(DB::raw('DATE(travels.travels_date_of_visit) AS DATE'), ('profiles.profile_country AS  COUNTRY'), DB::raw('COUNT(profiles.profile_country) AS COUNT'))
          ->groupBy('DATE','COUNTRY')
          ->orderBy('travels.travels_date_of_visit', 'DESC')
          ->get();

	    $daily_output = [];
	    foreach ($daily as $entry) {
	         $daily_output[$entry->DATE][$entry->COUNTRY] = $entry->COUNT;
	    }

	    return response()->json(["activity" => $total_output, "daily"=> $daily_output]);

	}

	public function getTotalCountOfTravelsPortOfEntry(Request $request)
	{
		$start = Carbon::parse($request->date_from)->format('Y-m-d');
	    $end   = Carbon::parse($request->date_to)->format('Y-m-d');

        $total = Db::table('travels')
          ->whereDate('travels.travels_date_of_visit', '>=', $start)
          ->whereDate('travels.travels_date_of_visit', '<=', $end)
          ->join('profiles','travels.profile_id','=','profiles.id')
          ->select(('travels.travels_port_of_entry AS NAME'), DB::raw('COUNT(travels.travels_port_of_entry) AS COUNT'))
          ->groupBy('NAME')
          ->get();

         $total_output = [];
         foreach ($total as $entry) {
             $total_output[$entry->NAME] = $entry->COUNT;
        }

	    $daily = Db::table('travels')
	      ->whereDate('travels.travels_date_of_visit', '>=', $start)
          ->whereDate('travels.travels_date_of_visit', '<=', $end)
          ->join('profiles','travels.profile_id','=','profiles.id')
          ->select(DB::raw('DATE(travels.travels_date_of_visit) AS DATE'), ('travels.travels_port_of_entry AS NAME'), DB::raw('COUNT(profiles.profile_country) AS COUNT'))
          ->groupBy('DATE','NAME')
          ->orderBy('travels.travels_date_of_visit', 'DESC')
          ->get();

	    $daily_output = [];
	    foreach ($daily as $entry) {
	         $daily_output[$entry->DATE][$entry->NAME] = $entry->COUNT;
	    }

	    return response()->json(["total" => $total_output, "daily"=> $daily_output]);

	}


	public function getTotalCountOfTravelsPortOfExit(Request $request)
	{

		$start = Carbon::parse($request->date_from)->format('Y-m-d');
	    $end   = Carbon::parse($request->date_to)->format('Y-m-d');

        $total = Db::table('travels')
          ->whereDate('travels.travels_date_of_visit', '>=', $start)
          ->whereDate('travels.travels_date_of_visit', '<=', $end)
          ->join('profiles','travels.profile_id','=','profiles.id')
          ->select(('travels.travels_port_of_exit AS NAME'), DB::raw('COUNT(travels.travels_port_of_exit) AS COUNT'))
          ->groupBy('NAME')
          ->get();

         $total_output = [];
         foreach ($total as $entry) {
             $total_output[$entry->NAME] = $entry->COUNT;
        }

	    $daily = Db::table('travels')
	      ->whereDate('travels.travels_date_of_visit', '>=', $start)
          ->whereDate('travels.travels_date_of_visit', '<=', $end)
          ->join('profiles','travels.profile_id','=','profiles.id')
          ->select(DB::raw('DATE(travels.travels_date_of_visit) AS DATE'), ('travels.travels_port_of_exit AS NAME'), DB::raw('COUNT(profiles.profile_country) AS COUNT'))
          ->groupBy('DATE','NAME')
          ->orderBy('travels.travels_date_of_visit', 'DESC')
          ->get();

	    $daily_output = [];
	    foreach ($daily as $entry) {
	         $daily_output[$entry->DATE][$entry->NAME] = $entry->COUNT;
	    }

	    return response()->json(["total" => $total_output, "daily"=> $daily_output]);

	}

	public function getTotalCountByAge(Request $request)
	{

	    $start = Carbon::parse($request->date_from)->format('Y-m-d');
	    $end = Carbon::parse($request->date_to)->format('Y-m-d');
	    // $offset   = $request->input('offset');
	    // $limit    = $request->input('limit');
	    
	    $total = Db::table('travels')
	       ->whereDate('travels.travels_date_of_visit', '>=', $start)
	       ->whereDate('travels.travels_date_of_visit', '<=', $end)
	       ->join('profiles','travels.profile_id','profiles.id')
	       ->select(DB::raw('CASE 
	          WHEN profiles.profile_age <= 17 THEN "Below 18" 
	          WHEN profiles.profile_age >= 18 AND profiles.profile_age < 29 THEN "18-29" 
	          WHEN profiles.profile_age > 30 AND profiles.profile_age < 49 THEN "30-49"
	          WHEN profiles.profile_age > 50 AND profiles.profile_age < 59 THEN "50-59" 
	          WHEN profiles.profile_age > 60 THEN "ABOVE 60"  
	          END AS ages'), DB::raw('COUNT(profiles.profile_age) AS AGE'))
	       ->groupBy('ages')
	       ->orderBy('ages', 'ASC')
	       // ->offset($offset)->limit($limit)
	       ->get();

	    $total_output = [];
	    foreach ($total as $entry) {
	        $total_output[$entry->ages] = $entry->AGE;
	    }

	    $total_daily = Db::table('travels')
	       ->whereDate('travels.travels_date_of_visit', '>=', $start)
	       ->whereDate('travels.travels_date_of_visit', '<=', $end)
	       ->join('profiles','travels.profile_id','profiles.id')
	       ->select(DB::raw('DATE(travels.travels_date_of_visit) AS DATE'), DB::raw('CASE 
	          WHEN profiles.profile_age <= 17 THEN "Below 18" 
	          WHEN profiles.profile_age >= 18 AND profiles.profile_age < 29 THEN "18-29" 
	          WHEN profiles.profile_age > 30 AND profiles.profile_age < 49 THEN "30-49"
	          WHEN profiles.profile_age > 50 AND profiles.profile_age < 59 THEN "50-59" 
	          WHEN profiles.profile_age > 60 THEN "ABOVE 60"  
	          END AS ages'), DB::raw('COUNT(profiles.profile_age) AS AGE'))
	       ->groupBy('DATE','ages')
	       ->orderBy('travels.travels_date_of_visit', 'DESC')
	       // ->offset($offset)->limit($limit)
	       ->get();

	    $daily_output = [];
	    foreach ( $total_daily as $entry) {
	        $daily_output[$entry->DATE][$entry->ages] = $entry->AGE;
	    }
	    
	    return response()->json(["total" => $total_output, 'daily'=>$daily_output]);
	}
}

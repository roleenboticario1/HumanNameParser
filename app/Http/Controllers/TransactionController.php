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
	
	public function getTotalCount(Request $request)
  {
       
      $start = Carbon::parse($request->date_from)->format('Y-m-d');
	    $end   = Carbon::parse($request->date_to)->format('Y-m-d');

      $total = Db::table('travels')
        ->whereDate('travels.travels_date_of_visit', '>=', $start)
        ->whereDate('travels.travels_date_of_visit', '<=', $end)
        ->join('profiles','travels.profile_id','=','profiles.profile_id')
        ->select(('travels.travels_activity AS ACTIVITY'), DB::raw('COUNT(travels.travels_date_of_visit) AS COUNT'))
        ->groupBy('ACTIVITY')
        ->get();

      $total_output = [];
      foreach ($total as $entry) {
           $total_output[$entry->ACTIVITY] = $entry->COUNT;
      }

	    $daily = Db::table('travels')
	      ->whereDate('travels.travels_date_of_visit', '>=', $start)
        ->whereDate('travels.travels_date_of_visit', '<=', $end)
        ->join('profiles','travels.profile_id','=','profiles.profile_id')
        ->select(DB::raw('DATE(travels.travels_date_of_visit) AS DATE'), ('travels.travels_activity AS ACTIVITY'), DB::raw('COUNT(profiles.profile_gender) AS COUNT'))
        ->groupBy('DATE','ACTIVITY')
        ->orderBy('travels.travels_date_of_visit', 'DESC')
        ->get();

	    $daily_output = [];
	    foreach ($daily as $key => $entry) {
	         $daily_output[$entry->DATE][$entry->ACTIVITY] = $entry->COUNT;
	    }

        $results = [
          'date' => $daily_output 
      ];

      
      return response()->json(["activity" =>$total_output , "daily"=>[$results]]);
	}

	
  public function getTotalCountByGender(Request $request)
  {
       
      $start = Carbon::parse($request->date_from)->format('Y-m-d');
	    $end   = Carbon::parse($request->date_to)->format('Y-m-d');

      $total = Db::table('travels')
        ->whereDate('travels.travels_date_of_visit', '>=', $start)
        ->whereDate('travels.travels_date_of_visit', '<=', $end)
        ->join('profiles','travels.profile_id','=','profiles.profile_id')
        ->select(('profiles.profile_gender AS GENDER'), ('travels.travels_activity AS ACTIVITY'), DB::raw('COUNT(profiles.profile_gender) AS COUNT'))
        ->groupBy('GENDER','ACTIVITY')
        ->get();

      $total_output = [];
      foreach ($total as $entry) {
           $total_output[$entry->ACTIVITY][" ".$entry->GENDER] = $entry->COUNT;
      }

	    $daily = Db::table('travels')
	      ->whereDate('travels.travels_date_of_visit', '>=', $start)
        ->whereDate('travels.travels_date_of_visit', '<=', $end)
        ->join('profiles','travels.profile_id','=','profiles.profile_id')
        ->select(DB::raw('DATE(travels.travels_date_of_visit) AS DATE'), ('travels.travels_activity AS ACTIVITY'), ('profiles.profile_gender as GENDER'), DB::raw('COUNT(profiles.profile_gender) AS COUNT'))
        ->groupBy('DATE','GENDER','ACTIVITY')
        ->orderBy('travels.travels_date_of_visit', 'DESC')
        ->get();
      
	    $daily_output = [];
	    foreach ($daily as $key => $entry) {
	        $daily_output[$entry->DATE][$entry->ACTIVITY][" ".$entry->GENDER] = $entry->COUNT;
        
	    }

      $results = [
          'date' => $daily_output 
      ];
       
      return response()->json(["activity" => $total_output, "daily"=> [ $results] ]);
	}

  
  public function getTotalCountByNationality(Request $request)
  {
     
      $start = Carbon::parse($request->date_from)->format('Y-m-d');
	    $end   = Carbon::parse($request->date_to)->format('Y-m-d');

      $total = Db::table('travels')
        ->whereDate('travels.travels_date_of_visit', '>=', $start)
        ->whereDate('travels.travels_date_of_visit', '<=', $end)
        ->join('profiles','travels.profile_id','=','profiles.profile_id')
        ->select(('profiles.profile_nationality AS  NATIONALITY'), ('travels.travels_activity AS ACTIVITY'), DB::raw('COUNT(profiles.profile_nationality) AS COUNT'))
        ->groupBy('NATIONALITY','ACTIVITY')
        ->get();

      $total_output = [];
      foreach ($total as $entry) {
         $total_output[$entry->ACTIVITY][$entry->NATIONALITY] = $entry->COUNT;
      }

	    $daily = Db::table('travels')
	      ->whereDate('travels.travels_date_of_visit', '>=', $start)
        ->whereDate('travels.travels_date_of_visit', '<=', $end)
        ->join('profiles','travels.profile_id','=','profiles.profile_id')
        ->select(DB::raw('DATE(travels.travels_date_of_visit) AS DATE'), ('travels.travels_activity AS ACTIVITY'),('profiles.profile_nationality AS NATIONALITY'), DB::raw('COUNT(profiles.profile_nationality) AS COUNT'))
        ->groupBy('DATE','NATIONALITY','ACTIVITY')
        ->orderBy('travels.travels_date_of_visit', 'DESC')
        ->get();

	    $daily_output = [];
	    foreach ($daily as $key => $entry) {
	         $daily_output[$entry->DATE][$entry->ACTIVITY][$entry->NATIONALITY] = $entry->COUNT;
	    }

      $results = [
        'date' => $daily_output 
      ];


	    return response()->json(["activity" => $total_output, "daily"=> [$results ]]);
	}


  public function getTotalCountgenderByNationality(Request $request)
  {
     
      $start = Carbon::parse($request->date_from)->format('Y-m-d');
	    $end   = Carbon::parse($request->date_to)->format('Y-m-d');

      $total = Db::table('travels')
        ->whereDate('travels.travels_date_of_visit', '>=', $start)
        ->whereDate('travels.travels_date_of_visit', '<=', $end)
        ->join('profiles','travels.profile_id','=','profiles.profile_id')
        ->select(('profiles.profile_nationality AS  NATIONALITY'), ('travels.travels_activity AS ACTIVITY'),('profiles.profile_gender as GENDER'), DB::raw('COUNT(profiles.profile_nationality) AS COUNT'))
        ->groupBy('NATIONALITY','ACTIVITY','GENDER')
        ->get();

       $total_output = [];
       foreach ($total as $entry) {
           $total_output[$entry->ACTIVITY][" ".$entry->GENDER][$entry->NATIONALITY] = $entry->COUNT;
      } 

      $daily = Db::table('travels')
        ->whereDate('travels.travels_date_of_visit', '>=', $start)
        ->whereDate('travels.travels_date_of_visit', '<=', $end)
        ->join('profiles','travels.profile_id','=','profiles.profile_id')
        ->select(DB::raw('DATE(travels.travels_date_of_visit) AS DATE'), ('travels.travels_activity AS ACTIVITY'),('profiles.profile_gender as GENDER'),('profiles.profile_nationality AS NATIONALITY'), DB::raw('COUNT(profiles.profile_nationality) AS COUNT'))
        ->groupBy('DATE','NATIONALITY','ACTIVITY','GENDER')
        ->orderBy('travels.travels_date_of_visit', 'DESC')
        ->get();

      $daily_output = [];
      foreach ($daily as $key =>  $entry) {
         $daily_output[$entry->DATE][$entry->ACTIVITY][" ".$entry->GENDER][$entry->NATIONALITY] = $entry->COUNT;
      }

          $results = [
          'date' => $daily_output 
      ];

      return response()->json(["activity" => $total_output, "daily"=> [$results] ]);
	}

	public function getTotalCountOfTravelsPortOfEntry(Request $request)
	{
		  $start = Carbon::parse($request->date_from)->format('Y-m-d');
	    $end   = Carbon::parse($request->date_to)->format('Y-m-d');

      $total = Db::table('travels')
        ->whereDate('travels.travels_date_of_visit', '>=', $start)
        ->whereDate('travels.travels_date_of_visit', '<=', $end)
        ->join('profiles','travels.profile_id','=','profiles.profile_id')
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
        ->join('profiles','travels.profile_id','=','profiles.profile_id')
        ->select(DB::raw('DATE(travels.travels_date_of_visit) AS DATE'), ('travels.travels_port_of_entry AS NAME'), DB::raw('COUNT(profiles.profile_country) AS COUNT'))
        ->groupBy('DATE','NAME')
        ->orderBy('travels.travels_date_of_visit', 'DESC')
        ->get();

	    $daily_output = [];
	    foreach ($daily as $key =>  $entry) {
	         $daily_output[$entry->DATE][$entry->NAME] = $entry->COUNT;
	    }

      
      $results = [
          'date' => $daily_output 
      ];


	    return response()->json(["total" => $total_output, "daily"=> [$results]]);
	}

	public function getTotalCountGenderOfTravelsPortOfEntry(Request $request)
	{
		  $start = Carbon::parse($request->date_from)->format('Y-m-d');
	    $end   = Carbon::parse($request->date_to)->format('Y-m-d');

      $total = Db::table('travels')
        ->whereDate('travels.travels_date_of_visit', '>=', $start)
        ->whereDate('travels.travels_date_of_visit', '<=', $end)
        ->join('profiles','travels.profile_id','=','profiles.profile_id')
        ->select(('travels.travels_port_of_entry AS NAME'),('profiles.profile_gender as GENDER'), DB::raw('COUNT(travels.travels_port_of_entry) AS COUNT'))
        ->groupBy('NAME','GENDER')
        ->get();

       $total_output = [];
       foreach ($total as $entry) {
           $total_output[$entry->NAME][" ".$entry->GENDER] = $entry->COUNT;
      }

	    $daily = Db::table('travels')
	      ->whereDate('travels.travels_date_of_visit', '>=', $start)
        ->whereDate('travels.travels_date_of_visit', '<=', $end)
        ->join('profiles','travels.profile_id','=','profiles.profile_id')
        ->select(DB::raw('DATE(travels.travels_date_of_visit) AS DATE'),('profiles.profile_gender as GENDER'), ('travels.travels_port_of_entry AS NAME'), DB::raw('COUNT(profiles.profile_country) AS COUNT'))
        ->groupBy('DATE','GENDER','NAME')
        ->orderBy('travels.travels_date_of_visit', 'DESC')
        ->get();

	    $daily_output = [];
	    foreach ($daily as $key => $entry) {
	       $daily_output[$entry->DATE][$entry->NAME][" ".$entry->GENDER] = $entry->COUNT;
	    }

       $results = [
          'date' => $daily_output 
      ];


	    return response()->json(["total" => $total_output, "daily"=> [$results]]);
	}


	public function getTotalCountOfTravelsPortOfExit(Request $request)
	{

		  $start = Carbon::parse($request->date_from)->format('Y-m-d');
	    $end   = Carbon::parse($request->date_to)->format('Y-m-d');

      $total = Db::table('travels')
        ->whereDate('travels.travels_date_of_visit', '>=', $start)
        ->whereDate('travels.travels_date_of_visit', '<=', $end)
        ->join('profiles','travels.profile_id','=','profiles.profile_id')
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
        ->join('profiles','travels.profile_id','=','profiles.profile_id')
        ->select(DB::raw('DATE(travels.travels_date_of_visit) AS DATE'), ('travels.travels_port_of_exit AS NAME'), DB::raw('COUNT(profiles.profile_country) AS COUNT'))
        ->groupBy('DATE','NAME')
        ->orderBy('travels.travels_date_of_visit', 'DESC')
        ->get();

	    $daily_output = [];
	    foreach ($daily as $key => $entry) {
	       $daily_output[$entry->DATE][$entry->NAME] = $entry->COUNT;
	    }

         $results = [
          'date' => $daily_output 
      ];


	    return response()->json(["total" => $total_output, "daily"=> [$results]]);
	}

	public function getTotalCounGendertOfTravelsPortOfExit(Request $request)
	{

		  $start = Carbon::parse($request->date_from)->format('Y-m-d');
	    $end   = Carbon::parse($request->date_to)->format('Y-m-d');

      $total = Db::table('travels')
        ->whereDate('travels.travels_date_of_visit', '>=', $start)
        ->whereDate('travels.travels_date_of_visit', '<=', $end)
        ->join('profiles','travels.profile_id','=','profiles.profile_id')
        ->select(('travels.travels_port_of_exit AS NAME'), ('profiles.profile_gender as GENDER'), DB::raw('COUNT(travels.travels_port_of_exit) AS COUNT'))
        ->groupBy('NAME','GENDER')
        ->get();

      $total_output = [];
      foreach ($total as $entry) {
         $total_output[$entry->NAME][" ".$entry->GENDER] = $entry->COUNT;
      }

	    $daily = Db::table('travels')
	      ->whereDate('travels.travels_date_of_visit', '>=', $start)
        ->whereDate('travels.travels_date_of_visit', '<=', $end)
        ->join('profiles','travels.profile_id','=','profiles.profile_id')
        ->select(DB::raw('DATE(travels.travels_date_of_visit) AS DATE'), ('travels.travels_port_of_exit AS NAME'), ('profiles.profile_gender as GENDER'), DB::raw('COUNT(profiles.profile_country) AS COUNT'))
        ->groupBy('DATE','NAME','GENDER')
        ->orderBy('travels.travels_date_of_visit', 'DESC')
        ->get();

	    $daily_output = [];
	    foreach ($daily as $key=> $entry) {
	         $daily_output[$entry->DATE][$entry->NAME][" ".$entry->GENDER] = $entry->COUNT;
	    }

        $results = [
          'date' => $daily_output 
      ];

	    return response()->json(["total" => $total_output, "daily"=>  [$results]]);
	}
}

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
	public function getUsersProfile(Request $request)
	{
	    $start    =  Carbon::parse($request->date_from)->format('Y-m-d');
	    $end      =  Carbon::parse($request->date_to)->format('Y-m-d');
	    $offset   =  $request->input('offset');
	    $limit    =  $request->input('limit');

	    if ($limit > 1000) {
	      return response()->json(["message" => "Request Failed."]);
	    }

	    $data = DB::table('travels')
	      ->whereDate('travels.travels_date_of_visit', '>=', $start)
	      ->WhereDate('travels.travels_date_of_visit', '<=', $end)
	      ->join('profiles', 'travels.profile_id', '=', 'profiles.profile_id')
	      ->join('passports', 'travels.profile_id', '=', 'passports.profile_id')
	      ->select('travels.*','profiles.*','passports.*')
	      ->orderBy('travels.created_at', 'DESC')
	      ->offset($offset)->limit($limit)->get();

	     return response()->json(["result" => $data]);
	}

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
	    foreach ($daily as $entry) {
	         $daily_output["date ".$entry->DATE][$entry->ACTIVITY] = $entry->COUNT;
	    }
      
      return response()->json(["activity" =>$total_output , "daily"=>[$daily_output]]);
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
	    foreach ($daily as $key=> $entry) {
	        $daily_output[$entry->DATE][$entry->ACTIVITY][" ".$entry->GENDER] = $entry->COUNT;
	    }

      return response()->json(["activity" => $total_output, "daily"=> [$daily_output]]);
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
	    foreach ($daily as $entry) {
	         $daily_output[$entry->DATE][$entry->ACTIVITY][$entry->NATIONALITY] = $entry->COUNT;
	    }

	    return response()->json(["activity" => $total_output, "daily"=> [$daily_output]]);
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
      foreach ($daily as $entry) {
         $daily_output[$entry->DATE][$entry->ACTIVITY][" ".$entry->GENDER][$entry->NATIONALITY] = $entry->COUNT;
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
	    foreach ($daily as $entry) {
	         $daily_output[$entry->DATE][$entry->NAME] = $entry->COUNT;
	    }

	    return response()->json(["total" => $total_output, "daily"=> [$daily_output]]);
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
	    foreach ($daily as $entry) {
	       $daily_output[$entry->DATE][$entry->NAME][" ".$entry->GENDER] = $entry->COUNT;
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
	    foreach ($daily as $entry) {
	       $daily_output[$entry->DATE][$entry->NAME] = $entry->COUNT;
	    }

	    return response()->json(["total" => $total_output, "daily"=> [$daily_output]]);
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
	    foreach ($daily as $entry) {
	         $daily_output[$entry->DATE][$entry->NAME][" ".$entry->GENDER] = $entry->COUNT;
	    }

	    return response()->json(["total" => $total_output, "daily"=> $daily_output]);
	}

	// public function getTotalCountByAge(Request $request)
	// {

	//     $start = Carbon::parse($request->date_from)->format('Y-m-d');
	//     $end = Carbon::parse($request->date_to)->format('Y-m-d');
	//     // $offset   = $request->input('offset');
	//     // $limit    = $request->input('limit');
	    
	//     $total = Db::table('travels')
	//        ->whereDate('travels.travels_date_of_visit', '>=', $start)
	//        ->whereDate('travels.travels_date_of_visit', '<=', $end)
	//        ->join('profiles','travels.profile_id','profiles.profile_id')
	//        ->select(DB::raw('CASE 
	//           WHEN profiles.profile_age <= 17 THEN "Below 18" 
	//           WHEN profiles.profile_age >= 18 AND profiles.profile_age < 29 THEN "18-29" 
	//           WHEN profiles.profile_age > 30 AND profiles.profile_age < 49 THEN "30-49"
	//           WHEN profiles.profile_age > 50 AND profiles.profile_age < 59 THEN "50-59" 
	//           WHEN profiles.profile_age > 60 THEN "ABOVE 60"  
	//           END AS ages'), ('travels.travels_activity AS ACTIVITY'),DB::raw('COUNT(profiles.profile_age) AS AGE'))
	//        ->groupBy('ages','ACTIVITY')
	//        ->orderBy('ages', 'DESC')
	//        // ->offset($offset)->limit($limit)
	//        ->get();

	//     $total_output = [];
	//     foreach ($total as $entry) {
	//         $total_output[$entry->ACTIVITY][$entry->ages]= $entry->AGE;
	//     }

	//     $total_daily = Db::table('travels')
	//        ->whereDate('travels.travels_date_of_visit', '>=', $start)
	//        ->whereDate('travels.travels_date_of_visit', '<=', $end)
	//        ->join('profiles','travels.profile_id','profiles.profile_id')
	//        ->select(DB::raw('DATE(travels.travels_date_of_visit) AS DATE'), DB::raw('CASE 
	//           WHEN profiles.profile_age <= 17 THEN "Below 18" 
	//           WHEN profiles.profile_age >= 18 AND profiles.profile_age < 29 THEN "18-29" 
	//           WHEN profiles.profile_age > 30 AND profiles.profile_age < 49 THEN "30-49"
	//           WHEN profiles.profile_age > 50 AND profiles.profile_age < 59 THEN "50-59" 
	//           WHEN profiles.profile_age > 60 THEN "ABOVE 60"  
	//           END AS ages'),('travels.travels_activity AS ACTIVITY'), DB::raw('COUNT(profiles.profile_age) AS AGE'))
	//        ->groupBy('DATE','ages','ACTIVITY')
	//        ->orderBy('travels.travels_date_of_visit', 'DESC')
	//        // ->offset($offset)->limit($limit)
	//        ->get();

	//     $daily_output = [];
	//     foreach ( $total_daily as $entry) {
	//         $daily_output[$entry->DATE][$entry->ACTIVITY][$entry->ages] = $entry->AGE;
	//     }
	    
	//     return response()->json(["total" => $total_output, 'daily'=>$daily_output]);
	// }

	// public function getTotalCountGenderByAge(Request $request)
	// {

	//     $start = Carbon::parse($request->date_from)->format('Y-m-d');
	//     $end = Carbon::parse($request->date_to)->format('Y-m-d');
	//     // $offset   = $request->input('offset');
	//     // $limit    = $request->input('limit');
	    
	//     $total = Db::table('travels')
	//        ->whereDate('travels.travels_date_of_visit', '>=', $start)
	//        ->whereDate('travels.travels_date_of_visit', '<=', $end)
	//        ->join('profiles','travels.profile_id','profiles.id')
	//        ->select(DB::raw('CASE 
	//           WHEN profiles.profile_age <= 17 THEN "Below 18" 
	//           WHEN profiles.profile_age >= 18 AND profiles.profile_age < 29 THEN "18-29" 
	//           WHEN profiles.profile_age > 30 AND profiles.profile_age < 49 THEN "30-49"
	//           WHEN profiles.profile_age > 50 AND profiles.profile_age < 59 THEN "50-59" 
	//           WHEN profiles.profile_age > 60 THEN "ABOVE 60"  
	//           END AS ages'), ('travels.travels_activity AS ACTIVITY'),('profiles.profile_gender as GENDER'),DB::raw('COUNT(profiles.profile_age) AS AGE'))
	//        ->groupBy('ages','ACTIVITY','GENDER')
	//        ->orderBy('ages', 'DESC')
	//        // ->offset($offset)->limit($limit)
	//        ->get();

	//     $total_output = [];
	//     foreach ($total as $entry) {
	//         $total_output[$entry->ACTIVITY][" ".$entry->GENDER][$entry->ages]= $entry->AGE;
	//     }

	//     $total_daily = Db::table('travels')
	//        ->whereDate('travels.travels_date_of_visit', '>=', $start)
	//        ->whereDate('travels.travels_date_of_visit', '<=', $end)
	//        ->join('profiles','travels.profile_id','profiles.id')
	//        ->select(DB::raw('DATE(travels.travels_date_of_visit) AS DATE'), DB::raw('CASE 
	//           WHEN profiles.profile_age <= 17 THEN "Below 18" 
	//           WHEN profiles.profile_age >= 18 AND profiles.profile_age < 29 THEN "18-29" 
	//           WHEN profiles.profile_age > 30 AND profiles.profile_age < 49 THEN "30-49"
	//           WHEN profiles.profile_age > 50 AND profiles.profile_age < 59 THEN "50-59" 
	//           WHEN profiles.profile_age > 60 THEN "ABOVE 60"  
	//           END AS ages'),('travels.travels_activity AS ACTIVITY'),('profiles.profile_gender as GENDER'), DB::raw('COUNT(profiles.profile_age) AS AGE'))
	//        ->groupBy('DATE','ages','ACTIVITY','GENDER')
	//        ->orderBy('travels.travels_date_of_visit', 'DESC')
	//        // ->offset($offset)->limit($limit)
	//        ->get();

	//     $daily_output = [];
	//     foreach ( $total_daily as $entry) {
	//         $daily_output[$entry->DATE][$entry->ACTIVITY][" ".$entry->GENDER][$entry->ages] = $entry->AGE;
	//     }
	    
	//     return response()->json(["total" => $total_output, 'daily'=>$daily_output]);
	// }
}

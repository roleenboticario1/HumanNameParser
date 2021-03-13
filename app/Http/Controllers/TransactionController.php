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
      $updated_by = $request->input('updated_by');

      if($updated_by == "") {
      $updated_by_Array = [0, 1];
      } else {
      $updated_by_Array = [$updated_by];
      }
    
      $total = DB::table('travels')
        ->whereIn('travels.updated_by', $updated_by_Array)
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

      $daily = DB::table('travels')
        ->whereIn('travels.updated_by', $updated_by_Array)
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

      return response()->json(["activity" =>$total_output , "daily"=>[$daily_output]]);
  }

  
  public function getTotalCountByGender(Request $request)
  {
       
      $start = Carbon::parse($request->date_from)->format('Y-m-d');
      $end   = Carbon::parse($request->date_to)->format('Y-m-d');
      $updated_by = $request->input('updated_by');

      if($updated_by == "") {
      $updated_by_Array = [0, 1];
      } else {
      $updated_by_Array = [$updated_by];
      }

      $total = DB::table('travels')
        ->whereIn('travels.updated_by', $updated_by_Array)
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

      $daily = DB::table('travels')
        ->whereIn('travels.updated_by', $updated_by_Array)
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

      return response()->json(["activity" =>$total_output , "daily"=>[$daily_output]]);
  }

  
  public function getTotalCountByNationality(Request $request)
  {
     
      $start = Carbon::parse($request->date_from)->format('Y-m-d');
      $end   = Carbon::parse($request->date_to)->format('Y-m-d');

      $updated_by = $request->input('updated_by');

      if($updated_by == "") {
      $updated_by_Array = [0, 1];
      } else {
      $updated_by_Array = [$updated_by];
      }
    

      $total = DB::table('travels')
        ->whereIn('travels.updated_by', $updated_by_Array)
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

      $daily = DB::table('travels')
        ->whereIn('travels.updated_by', $updated_by_Array)
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

      return response()->json(["activity" =>$total_output , "daily"=>[$daily_output]]);
  }


  public function getTotalCountOfTravelsPortOfEntry(Request $request)
  {
      $start = Carbon::parse($request->date_from)->format('Y-m-d');
      $end   = Carbon::parse($request->date_to)->format('Y-m-d');
      $updated_by = $request->input('updated_by');

      if($updated_by == "") {
      $updated_by_Array = [0, 1];
      } else {
      $updated_by_Array = [$updated_by];
      }

      $total = DB::table('travels')
        ->whereIn('travels.updated_by', $updated_by_Array)
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

      $daily = DB::table('travels')
        ->whereIn('travels.updated_by', $updated_by_Array)
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

      return response()->json(["activity" =>$total_output , "daily"=>[$daily_output]]);
  }


  public function getTotalCountOfTravelsPortOfExit(Request $request)
  {

      $start = Carbon::parse($request->date_from)->format('Y-m-d');
      $end   = Carbon::parse($request->date_to)->format('Y-m-d');
      $updated_by = $request->input('updated_by');

      if($updated_by == "") {
      $updated_by_Array = [0, 1];
      } else {
      $updated_by_Array = [$updated_by];
      }
    

      $total = DB::table('travels')
        ->whereIn('travels.updated_by', $updated_by_Array)
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

      $daily = DB::table('travels')
        ->whereIn('travels.updated_by', $updated_by_Array)
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

      return response()->json(["activity" =>$total_output , "daily"=>[$daily_output]]);
  }

  public function getTotalCountEntryPortByNationality(Request $request)
  {
      $start = Carbon::parse($request->date_from)->format('Y-m-d');
      $end = Carbon::parse($request->date_to)->format('Y-m-d');
      $updated_by = $request->input('updated_by');

      if($updated_by == "") {
      $updated_by_Array = [0, 1];
      } else {
      $updated_by_Array = [$updated_by];
      }
    
      $total = DB::table('travels')
         ->whereIn('travels.updated_by', $updated_by_Array)
         ->whereDate('travels.travels_date_of_visit', '>=', $start)
         ->whereDate('travels.travels_date_of_visit', '<=', $end)
         ->join('profiles','travels.profile_id','profiles.profile_id')
         ->select(DB::raw('profiles.profile_nationality AS NATIONALITY'), ('travels.travels_port_of_entry AS ENTRY'), DB::raw('COUNT(travels.travels_port_of_entry) AS COUNT'))
         ->groupBy(['NATIONALITY','ENTRY'])
         ->orderBy('travels.travels_date_of_visit', 'DESC')
         ->get();

      $total_output  = [];
      foreach ( $total as $entry) {
          $total_output [$entry->NATIONALITY][$entry->ENTRY]= $entry->COUNT;
      }

      $total_daily = DB::table('travels')
         ->whereIn('travels.updated_by', $updated_by_Array)
         ->whereDate('travels.travels_date_of_visit', '>=', $start)
         ->whereDate('travels.travels_date_of_visit', '<=', $end)
         ->join('profiles','travels.profile_id','profiles.profile_id')
         ->select(DB::raw('DATE(travels.travels_date_of_visit) AS DATE'), DB::raw('profiles.profile_nationality AS NATIONALITY'),DB::raw('travels.travels_port_of_entry AS ENTRY'), DB::raw('COUNT(travels.travels_port_of_entry) AS COUNT'))
         ->groupBy('DATE','NATIONALITY','ENTRY')
         ->orderBy('travels.travels_date_of_visit', 'DESC')
         ->get();

      $daily_output = [];
      foreach ( $total_daily as $entry) {
          $daily_output[$entry->DATE][$entry->NATIONALITY][$entry->ENTRY]= $entry->COUNT;

      }
      
      return response()->json(['total' => $total_output,'daily'=>[$daily_output] ]);
  }

  public function getTotalCountExitPortByNationality(Request $request)
  {
      $start = Carbon::parse($request->date_from)->format('Y-m-d');
      $end = Carbon::parse($request->date_to)->format('Y-m-d');
      $updated_by = $request->input('updated_by');

      if($updated_by == "") {
      $updated_by_Array = [0, 1];
      } else {
      $updated_by_Array = [$updated_by];
      }
  
      $total = DB::table('travels')
         ->whereIn('travels.updated_by', $updated_by_Array)
         ->whereDate('travels.travels_date_of_visit', '>=', $start)
         ->whereDate('travels.travels_date_of_visit', '<=', $end)
         ->join('profiles','travels.profile_id','profiles.profile_id')
         ->select(DB::raw('profiles.profile_nationality AS NATIONALITY'), ('travels.travels_port_of_exit AS EXIT'), DB::raw('COUNT(travels.travels_port_of_exit) AS COUNT'))
         ->groupBy(['NATIONALITY','EXIT'])
         ->orderBy('travels.travels_date_of_visit', 'DESC')
         ->get();

      $total_output  = [];
      foreach ( $total as $entry) {
          $total_output [$entry->NATIONALITY][$entry->EXIT]= $entry->COUNT;
      }

      $total_daily = Db::table('travels')
         ->whereIn('travels.updated_by', $updated_by_Array)
         ->whereDate('travels.travels_date_of_visit', '>=', $start)
         ->whereDate('travels.travels_date_of_visit', '<=', $end)
         ->join('profiles','travels.profile_id','profiles.profile_id')
         ->select(DB::raw('DATE(travels.travels_date_of_visit) AS DATE'), DB::raw('profiles.profile_nationality AS NATIONALITY'),DB::raw('travels.travels_port_of_exit AS ENTRY'), DB::raw('COUNT(travels.travels_port_of_exit) AS COUNT'))
         ->groupBy('DATE','NATIONALITY','ENTRY')
         ->orderBy('travels.travels_date_of_visit', 'DESC')
         ->get();

      $daily_output = [];
      foreach ( $total_daily as $entry) {
          $daily_output[$entry->DATE][$entry->NATIONALITY][$entry->ENTRY]= $entry->COUNT;

      }

      return response()->json(['total' => $total_output,'daily'=>[$daily_output] ]);
  }


  public function getTotalCountEntryPortAndExitPortByNationality(Request $request)
  {
      $start = Carbon::parse($request->date_from)->format('Y-m-d');
      $end = Carbon::parse($request->date_to)->format('Y-m-d');
      $updated_by = $request->input('updated_by');


      if($updated_by == "") {
      $updated_by_Array = [0, 1];
      } else {
      $updated_by_Array = [$updated_by];
      }

      $total = DB::table('travels')
        ->whereIn('travels.updated_by', $updated_by_Array)
         ->whereDate('travels.travels_date_of_visit', '>=', $start)
         ->whereDate('travels.travels_date_of_visit', '<=', $end)
         ->join('profiles','travels.profile_id','profiles.profile_id')
         ->select(DB::raw('profiles.profile_nationality AS NATIONALITY'), ('travels.travels_port_of_entry AS ENTRY'), 
                  DB::raw('COUNT(travels.travels_port_of_entry) AS ENT'), DB::raw('COUNT(travels.travels_port_of_exit) AS EXT'),
                 ('travels.travels_port_of_exit AS EXIT'))
          ->groupBy('NATIONALITY','ENTRY')
         ->orderBy('travels.travels_date_of_visit', 'DESC')
         ->get();

       $total_ = DB::table('travels')
         ->whereIn('travels.updated_by', $updated_by_Array)
         ->whereDate('travels.travels_date_of_visit', '>=', $start)
         ->whereDate('travels.travels_date_of_visit', '<=', $end)
         ->join('profiles','travels.profile_id','profiles.profile_id')
         ->select(DB::raw('profiles.profile_nationality AS NATIONALITY'), DB::raw('COUNT(travels.travels_port_of_exit) AS EXT'),
                 ('travels.travels_port_of_exit AS EXIT'))
          ->groupBy('NATIONALITY','EXIT')
         ->orderBy('travels.travels_date_of_visit', 'DESC')
         ->get();

      $total_output  = [];
      foreach ( $total as $entry) {
          $total_output [$entry->NATIONALITY]["Enrty Port"][$entry->ENTRY]= $entry->ENT;
              foreach($total_ as $entries)
              {
                 $total_output [$entries->NATIONALITY]["Exit Port"][$entries->EXIT]= $entry->EXT;
              }
      }

      $daily_total = DB::table('travels')
         ->whereIn('travels.updated_by', $updated_by_Array)
         ->whereDate('travels.travels_date_of_visit', '>=', $start)
         ->whereDate('travels.travels_date_of_visit', '<=', $end)
         ->join('profiles','travels.profile_id','profiles.profile_id')
         ->select(DB::raw('DATE(travels.travels_date_of_visit) AS DATE'),DB::raw('profiles.profile_nationality AS NATIONALITY'), ('travels.travels_port_of_entry AS ENTRY'), 
                  DB::raw('COUNT(travels.travels_port_of_entry) AS ENT'), DB::raw('COUNT(travels.travels_port_of_exit) AS EXT'),
                 ('travels.travels_port_of_exit AS EXIT'))
          ->groupBy('DATE','NATIONALITY','ENTRY')
         ->orderBy('travels.travels_date_of_visit', 'DESC')
         ->get();

       $daily_total_ = DB::table('travels')
        ->whereIn('travels.updated_by', $updated_by_Array)
         ->whereDate('travels.travels_date_of_visit', '>=', $start)
         ->whereDate('travels.travels_date_of_visit', '<=', $end)
         ->join('profiles','travels.profile_id','profiles.profile_id')
         ->select(DB::raw('DATE(travels.travels_date_of_visit) AS DATE'),DB::raw('profiles.profile_nationality AS NATIONALITY'), DB::raw('COUNT(travels.travels_port_of_exit) AS EXT'),
                 ('travels.travels_port_of_exit AS EXIT'))
          ->groupBy('DATE','NATIONALITY','EXIT')
         ->orderBy('travels.travels_date_of_visit', 'DESC')
         ->get();

      $daily_output  = [];
      foreach ( $daily_total as $entry) {
          $daily_output[$entry->DATE][$entry->NATIONALITY]["Enrty Port"][$entry->ENTRY]= $entry->ENT;
              foreach($daily_total_ as $entries)
              {
                 $daily_output[$entries->DATE][$entries->NATIONALITY]["Exit Port"][$entries->EXIT]= $entry->EXT;
              }
      }

      return response()->json(['total' => $total_output , 'daily'=>[$daily_output] ]);
  }
  

}


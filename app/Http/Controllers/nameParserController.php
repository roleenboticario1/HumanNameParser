<?php
//http://52.77.208.128:3000/oauth/login
//http://52.77.208.128:3000/oauth/login?username=admin@sample.com&password=adminpassword123&grant_type=password
//cd C:\xampp\htdocs\LumennameParsing3    //vendor\bin\phpunit --coverage-html report/
//$APIData = json_decode($response->getBody()->getContents(), true);  //return response()->json($APIData); 
//vendor\bin\phpunit tests

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\nameParser\nameParser;
use App\Http\Request\RequestManager;


class nameParserController extends Controller
{ 
    public function inputFullname(RequestManager $request) 
    { 
    	$input = $request->input('fullname');
        try{
	        $token = $request->bearerToken();
	        $client = new \GuzzleHttp\Client(); 
		    $response = $client->request('POST', 'http://52.77.208.128:3000/oauth/checkToken', [
		      'headers' =>  [
		          'Authorization' => 'Bearer '.$token,
		          'Content-Type' => 'application/x-www-form-urlencoded',
		          'Accept' => 'application/json',
		       ],
		    ]);
		    return $this->custom_validation($input); 
		}catch(\GuzzleHttp\Exception\BadResponseException $exception) {
		    return response()->json([
		        'errors'  => json_decode($exception->getResponse()->getBody()->getContents(), true)
		    ]);
		}
    }
   
    public function custom_validation($input)  
    {
		if(count(explode(' ',  $input)) <= 1)
	    {
		   return response()->json([
		   	  "status"=>false,
		      "message"=>"Please provide a valid name. One Character is not allowed."
		   ]);
		}
		if(count(explode(',', $input)) > 2)
		{
	        return response()->json([
		   	  "status"=>false,
		      "message"=>"Please provide a valid name. More than one comma is not allowed"
		    ]);
		}else{
		    $this->ParseName($input);
		} 
    }

    public function ParseName($input)  
    {     
	    $name = $input;
	    $np = new nameParser;	
	    $array  = [];
	    $np->setFullName($name);
	       $np->parse();
	    if(!$np->getNotParseable()) {
	        array_push($array, [
	          "title"=>$np->getTitle(),
	          "firstname"=> $np->getFirstName(),
	          "middlename"=>$np->getMiddleName(),
	          "lastname"=>$np->getLastName(),
	          "suffix"=>$np->getSuffix()
	        ]);
	    }
	    $json = json_encode($array);
	    echo  $json;	
    }

    public function showPayload(Request $request)
    {
        //Extract token from header
     	$token = $request->bearerToken();
        //Extract Payload
	    $tokenParts = explode('.',  $token);
		$header = base64_decode($tokenParts[0]);
		$payload = base64_decode($tokenParts[1]);
	    return $payload;
    }
}

    
    
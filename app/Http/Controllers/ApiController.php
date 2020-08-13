<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PreScreenedDonor;

class ApiController extends Controller
{
    public function authorization(Request $request){
	    $http = new \GuzzleHttp\Client;
	    // $client = DB::table('oauth_clients')->where('id', $request->client_id)->first();
	    // $user = App\User::find($client->user_id);
		try {
		    $response = $http->post(url('oauth/token'), [
		        'form_params' => [
		            'grant_type' => 'client_credentials',
		            'client_id' => $request->client_id,
		            'client_secret' => $request->client_secret,
		            'scope' => '*',
		        ],
		    ]);
		    // return $response->getBody();
		    $auth = json_decode((string) $response->getBody());	
		    // dd($auth);
		    if($auth){
		    	if($request->redirect == 'searchDonor'){
				    $res = $http->post(url('api/v1/android-search-donors'), [
				    	'form_params' => [
				            'searchString' => $request->searchString,
				        ],
				    	'headers' => [
				    		'Authorization' => 'Bearer '.$auth->access_token,
				    		'Accept'     => 'application/json',
				    	]
				    ]);
				    return $res->getBody();
		    	}else if($request->redirect == 'login'){
					$res = $http->post(url('api/v1/android-login'), [
				    	'form_params' => [
				            'user_id' => $request->user_id,
				            'password' => $request->password,
				        ],
				    	'headers' => [
				    		'Authorization' => 'Bearer '.$auth->access_token,
				    		'Accept'     => 'application/json',
				    	]
				    ]);
				    return $res->getBody();
		    	}else if($request->redirect == 'submitEntry'){
					$res = $http->post(url('api/v1/android-submit-entry'), [
				    	'form_params' => [
				            'first_name' => $request->first_name,
				            'middle_name' => $request->middle_name,
				            'last_name' => $request->last_name,
				            'name_suffix' => $request->name_suffix,
				            'nationality' => $request->nationality,
				            'gender' => $request->gender,
				            'bdate' => $request->bdate,
				            'age' => $request->age,
				            'weight' => $request->weight,
				            'address' => $request->address,
				            'email' => $request->email,
				            'fb' => $request->fb,
				            'contact' => $request->contact,
				            'time_availability' => $request->time_availability,
				            'first_answer' => $request->first_answer,
				            'second_answer' => $request->second_answer,
				            'not_sure_answer' => $request->not_sure_answer,		    		
				        ],
				    	'headers' => [
				    		'Authorization' => 'Bearer '.$auth->access_token,
				    		'Accept'     => 'application/json',
				    	]
				    ]);
				    return $res->getBody();
		    	}else if($request->redirect == 'donors'){
		    		$res = $http->get(url('api/v1/android-donors'), [
				    	'headers' => [
				    		'Authorization' => 'Bearer '.$auth->access_token,
				    		'Accept'     => 'application/json',
				    	]
				    ]);
				    return $res->getBody();
		    	}else{
		    		return response()->json(['message' => 'Redirect not found']);
		    	}
		    	// $res = $http->get(url('api/v1/android-search-donors'), [
				   //  	'form_params' => [
				   //          'user_id' => $request->user_id,
				   //          'password' => $request->password,
				   //      ],
				   //  	'headers' => [
				   //  		'Authorization' => 'Bearer '.$auth->access_token,
				   //  		'Accept'     => 'application/json',
				   //  	]
				   //  ]);
			    
		    }
		} catch (Exception $e) {
		    return response()->json([
		    	'message' => $e->getResponse()->getReasonPhrase()
		    	// 'statusCode' => $e->getResponse()->getStatusCode()
		    ]);
		}    
    }

    function login(Request $request){
		$user_id = $request->user_id;
		$password = md5($request->password);
		// return response()->json(['user_id' => $user_id]);
		$isUserExists = \App\User::where('user_id', $user_id)->where('password', $password)->exists();
		if($isUserExists){
			return response()->json(['exists' => 'y']);
		}else{
			return response()->json(['exists' => 'n']);
		}    	
    }



    public function androidCreate(Request $request){
        $data = $request->except('_token');


        // initialize values
        // $fname = $data['first_name'];
        // $mname = $data['middle_name'];
        // $lname = $data['last_name'];
        // $name_suffix = $data['name_suffix'];

        // $nationality = $data['nationality'];
        $gender = $request->gender == "Male" ? "M" : "F";

        // // $bdate = $data['bdate'];
        // $age = $data['age'];

        // $weight = $data['weight'];
        // $address = $data['address'];

        // $email = $data['email'];
        // $fb = $data['fb'];
        // $mobile_no = $data['contact'];
        
        // $time_availability = $data['time_availability'];

        // $first_answer = $data['first_answer'];
        // $test_results = $data['second_answer'];
        // $symptoms = $data['not_sure_answer'];

        $created_by = "android";
        $created_dt = date('Y-m-d H:i:s');

        // check if record exists before inserting new record
        $check_existing_record = PreScreenedDonor::where('first_name', '=', $request->first_name)
                                ->where('middle_name', '=', $request->middle_name)
                                ->where('last_name', '=', $request->last_name)
                                ->where('name_suffix', '=', $request->name_suffix)
                                // ->where('bdate', '=', $bdate)
                                ->first();
        \Log::info($check_existing_record);

        // if not exist then
        if($check_existing_record === null){
            $pre_screened_donor = new PreScreenedDonor;
            $pre_screened_donor->first_name = $request->first_name;
            $pre_screened_donor->middle_name = $request->middle_name;
            $pre_screened_donor->last_name = $request->last_name;
            $pre_screened_donor->name_suffix = $request->name_suffix ? $request->name_suffix : "";
            
            $pre_screened_donor->nationality = $request->nationality;
            $pre_screened_donor->gender = $gender;
            
            $pre_screened_donor->bdate = $request->bdate;
            $pre_screened_donor->age = $request->age;
            
            $pre_screened_donor->weight = $request->weight;
            $pre_screened_donor->address = $request->address;
            
            $pre_screened_donor->email = $request->email ? $request->email : "";
            $pre_screened_donor->fb = $request->fb ? $request->fb : '';
            $pre_screened_donor->mobile_no = $request->contact ? $request->contact : '';
            
            $pre_screened_donor->time_availability = $request->time_availability;
            
            $pre_screened_donor->first_answer = $request->first_answer;
            $pre_screened_donor->second_answer = $request->second_answer != null ? json_encode($request->second_answer) : "";
            $pre_screened_donor->not_sure_answer = $request->not_sure_answer != null ? json_encode($request->not_sure_answer) : "";

            $pre_screened_donor->created_by = $created_by;
            $pre_screened_donor->created_dt = $created_dt;

            $pre_screened_donor->save();
            \Log::info($pre_screened_donor);


            return response()->json([
                'status' => "ok"
            ]);

        } else{
            return response()->json([
                'message' => 'This Pre-screened Donor already exists.',
                'status' => 0
            ], 200);
        }
    }

    function getAllDonors(){
        return response()->json(PreScreenedDonor::all());
    }

    function searchDonor(Request $request){
        $input = $request->searchString;
        $filteredDonors = \App\PreScreenedDonor::where('first_name', 'like', '%'.$input."%")->
                                                 orWhere('middle_name', 'like', '%'.$input."%")->
                                                 orWhere('last_name', 'like', '%'.$input."%")->get();
        return response()->json($filteredDonors);        
    }    
}

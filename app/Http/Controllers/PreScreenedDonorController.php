<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\PreScreenedDonor;
use DB;

use App\Donor;
use Session;
use Carbon;

class PreScreenedDonorController extends Controller
{
    public function index(){
        $query = "  SELECT id, first_name, middle_name, last_name, name_suffix, gender, 
                    bdate, address, created_dt, status
                    FROM `pre_screened_donors` ORDER BY `created_dt` DESC ";
        $pre_screened_donors = DB::select($query);

        // \Log::info($pre_screened_donors);
        // $candidates = Candidate::orderBy('screen_dt', 'desc')->get();
        return $pre_screened_donors;
    }

    public function getDetails($id){
        $pre_screened_donor = PreScreenedDonor::where('id', $id)->first();
        // \Log::info($pre_screened_donor);
        return $pre_screened_donor;
    }
    
    public function update(Request $request, $id){
        $data = $request->except('_token');
        // \Log::info($data);

        // GET THE USER INFO
        $session = Session::get('userInfo');
        $facility_user = Session::get('userInfo')['user_id'];
        $facility_cd = Session::get('userInfo')['facility_cd'];

        // initialize data
        $facility_user = $facility_user;
        $facility_cd = $facility_cd;
        \Log::info($facility_user);

        $year_now = date('Y');              // 2020
        $donors_count = Donor::count(); 
        $donors_count = $donors_count + 1;

        $seqno = $facility_cd . $year_now . sprintf("%07d", $donors_count); // 1300620200000004

        $first_name = strtoupper($data['first_name']);
        $middle_name = strtoupper($data['middle_name']);
        $last_name = strtoupper($data['last_name']);
        $name_suffix = strtoupper($data['name_suffix']);

        $gender = $data['gender'];
        $bdate = $data['bdate'];

        // $civil_stat = $data['civil_stat'];
        // $occupation = strtoupper($data['occupation']);

        $email = $data['email'];
        $nationality = 137;                                                 // equivalent for filipino
        // $tel_no = $data['tel_no'];
        $mobile_no = $data['mobile_no'];
        $created_dt = date('Y-m-d H:i:s');
        $created_by = $facility_user;

        // check if this record already exist
        // CHECK FIRST IF DONOR ALREADY EXIST
        $check_donor = Donor::where('fname', '=', $first_name)
                        ->where('mname', '=', $middle_name)
                        ->where('lname', '=', $last_name)
                        ->where('name_suffix', '=', $name_suffix)
                        ->where('bdate', '=', $bdate)
                        ->first();
        // \Log::info($check_donor);
        
        // IF THIS DONOR DOESNT EXIST IN THE DATABASE THEN
        if($check_donor === null){       
            $donor = new Donor;
            $donor->seqno = $seqno;
            $donor->fname = $first_name;
            $donor->mname = $middle_name;
            $donor->lname = $last_name;
            $donor->name_suffix = $name_suffix;

            $donor->gender = $gender;
            $donor->bdate = $bdate;
            // $donor->civil_stat = $civil_stat;
            // $donor->occupation = $occupation;
            $donor->nationality = $nationality;
            // $donor->tel_no = $tel_no;
            $donor->mobile_no = $mobile_no;
            $donor->email = $email;
            $donor->facility_cd = $facility_cd;
            $donor->created_dt = $created_dt;
            $donor->created_by = $created_by;
            $donor->save();
            // \Log::info($donor);
            
            // UPDATE PRE-SCREENED DONOR TABLE
            $pre_screened_donor = PreScreenedDonor::where('id', $id)->first();
            $pre_screened_donor->donor_sn = $seqno;
            $pre_screened_donor->status = 1;
            $pre_screened_donor->approved_by = $created_by;
            $pre_screened_donor->approval_dt = $created_dt;
            $pre_screened_donor->save();
            // \Log::info($pre_screened_donor);

            return response()->json([
                'message' => 'New Donor has been added successfully.',
                'status' => 1
            ], 200);
        } 
        
        // ELSE IF THIS DONOR ALREADY EXIST THEN
        else{
            /*
            SELECT seqno FROM donors 
            WHERE fname = $fname, mname = $mname, lname = $lname, name_suffix = $name_suffix, bdate = $bdate
            */
            $seqno = Donor::select('seqno')
                    ->where('first_name', '=', $first_name)
                    ->where('middle_name', '=', $middle_name)
                    ->where('last_name', '=', $last_name)
                    ->where('name_suffix', '=', $name_suffix)
                    ->where('bdate', '=', $bdate)
                    ->first();
            // \Log::info($seqno);

             // UPDATE PRE-SCREENED DONOR TABLE
             $pre_screened_donor = PreScreenedDonor::where('id', $id)->first();
             $pre_screened_donor->donor_sn = $seqno->seqno;
             $pre_screened_donor->status = 1;
             $pre_screened_donor->approved_by = $created_by;
             $pre_screened_donor->approval_dt = $created_dt;
             $pre_screened_donor->save();
            //  \Log::info($pre_screened_donor);s
 

            return response()->json([
                'message' => 'This donor already exists, tagged donor with sequence number.',
                'status' => 0
            ], 200);
        }

    } // update function

    public function create(Request $request){
        $data = $request->except('_token');

        // GET THE USER INFO
        $session = Session::get('userInfo');
        $facility_user = Session::get('userInfo')['user_id'];
        $facility_cd = Session::get('userInfo')['facility_cd'];

        // initialize data
        $facility_user = $facility_user;
        $facility_cd = $facility_cd;
        \Log::info($facility_user);

        // initialize values
        $fname = $data['fname'];
        $mname = $data['mname'];
        $lname = $data['lname'];
        $name_suffix = $data['name_suffix'];

        $nationality = $data['nationality'];
        $gender = $data['gender'];

        $bdate = $data['bdate'];
        $age = $data['age'];

        $weight = $data['weight'];
        $address = $data['address'];

        $email = $data['email'];
        $fb = $data['fb'];
        $mobile_no = $data['mobile_no'];
        
        $time_availability = $data['time_availability'];

        $first_answer = $data['first_answer'];
        $test_results = $data['test_results'];
        $symptoms = $data['symptoms'];

        $created_by = $facility_user;
        $created_dt = date('Y-m-d H:i:s');

        // check if record exists before inserting new record
        $check_existing_record = PreScreenedDonor::where('first_name', '=', $fname)
                                ->where('middle_name', '=', $mname)
                                ->where('last_name', '=', $lname)
                                ->where('name_suffix', '=', $name_suffix)
                                ->where('bdate', '=', $bdate)
                                ->first();
        \Log::info($check_existing_record);

        // if not exist then
        if($check_existing_record === null){
            $pre_screened_donor = new PreScreenedDonor;
            $pre_screened_donor->first_name = $fname;
            $pre_screened_donor->middle_name = $mname;
            $pre_screened_donor->last_name = $lname;
            $pre_screened_donor->name_suffix = $name_suffix;
            
            $pre_screened_donor->nationality = $nationality;
            $pre_screened_donor->gender = $gender;
            
            $pre_screened_donor->bdate = $bdate;
            $pre_screened_donor->age = $age;
            
            $pre_screened_donor->weight = $weight;
            $pre_screened_donor->address = $address;
            
            $pre_screened_donor->email = $email;
            $pre_screened_donor->fb = $fb;
            $pre_screened_donor->mobile_no = $mobile_no;
            
            $pre_screened_donor->time_availability = $time_availability;
            
            $pre_screened_donor->first_answer = $first_answer;
            $pre_screened_donor->second_answer = implode(',', $test_results);
            $pre_screened_donor->not_sure_answer = implode(',', $symptoms);

            $pre_screened_donor->created_by = $created_by;
            $pre_screened_donor->created_dt = $created_dt;

            $pre_screened_donor->save();
            \Log::info($pre_screened_donor);


            return response()->json([
                'message' => 'Successfully added New Pre-screened Donor entry.',
                'status' => 1
            ], 200);

        } else{
            return response()->json([
                'message' => 'This Pre-screened Donor already exists.',
                'status' => 0
            ], 200);
        }



    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\PreScreenedDonor;
use DB;

use App\Donor;

class PreScreenedDonorController extends Controller
{
    public function index(){
        $query = "  SELECT id, fname, mname, lname, name_suffix, gender, bdate, address, screen_dt, approval_status
                    FROM `pre_screened_donors` ORDER BY `screen_dt` DESC ";
        $pre_screened_donors = DB::select($query);

        \Log::info($pre_screened_donors);
        // $candidates = Candidate::orderBy('screen_dt', 'desc')->get();
        return $pre_screened_donors;
    }

    public function getDetails($id){
        $pre_screened_donor = PreScreenedDonor::where('id', $id)->first();
        \Log::info($pre_screened_donor);
        return $pre_screened_donor;
    }
    
    public function update(Request $request, $id){
        $data = $request->except('_token');
        \Log::info($data);

        // initialize data
        $facility_user = '13001_mj';
        $facility_cd = 13001;

        $year_now = date('Y');          // 2020
        $donors_count = Donor::count(); 
        $donors_count = $donors_count + 1;

        $seqno = $facility_cd . $year_now . sprintf("%06d", $donors_count); // 130062020000004

        $fname = strtoupper($data['fname']);
        $mname = strtoupper($data['mname']);
        $lname = strtoupper($data['lname']);
        $name_suffix = strtoupper($data['name_suffix']);
        $gender = $data['gender'];
        $bdate = $data['bdate'];
        $civil_stat = $data['civil_stat'];
        $occupation = strtoupper($data['occupation']);
        $nationality = 137;     // equivalent for filipino
        $tel_no = $data['tel_no'];
        $mobile_no = $data['mobile_no'];
        $email = $data['email'];
        $created_dt = date('Y-m-d H:i:s');
        $created_by = $facility_user;

        // check if this record already exist
        // CHECK FIRST IF DONOR ALREADY EXIST
        $check_donor = Donor::where('fname', '=', $fname)
                        ->where('mname', '=', $mname)
                        ->where('lname', '=', $lname)
                        ->where('name_suffix', '=', $name_suffix)
                        ->where('bdate', '=', $bdate)
                        ->first();
        \Log::info($check_donor);
        
        // IF THIS DONOR DOESNT EXIST IN THE DATABASE THEN
        if($check_donor === null){       
            $donor = new Donor;
            $donor->seqno = $seqno;
            $donor->fname = $fname;
            $donor->mname = $mname;
            $donor->lname = $lname;
            $donor->name_suffix = $name_suffix;
            $donor->gender = $gender;
            $donor->bdate = $bdate;
            $donor->civil_stat = $civil_stat;
            $donor->occupation = $occupation;
            $donor->nationality = $nationality;
            $donor->tel_no = $tel_no;
            $donor->mobile_no = $mobile_no;
            $donor->email = $email;
            $donor->facility_cd = $facility_cd;
            $donor->created_dt = $created_dt;
            $donor->created_by = $created_by;
            $donor->save();
            \Log::info($donor);
            
            // UPDATE PRE-SCREENED DONOR TABLE
            $pre_screened_donor = PreScreenedDonor::where('id', $id)->first();
            $pre_screened_donor->donor_sn = $seqno;
            $pre_screened_donor->approval_status = 1;
            $pre_screened_donor->save();
            \Log::info($pre_screened_donor);

            return response()->json([
                'message' => 'New Donor has been added successfully.',
                'status' => 1
            ], 200);


            // ELSE IF THIS DONOR ALREADY EXIST THEN
        } else{
            // get the donor_sn first before updating the pre-screened-donor table
            /*
            SELECT seqno FROM donors 
            WHERE fname = $fname, mname = $mname, lname = $lname, name_suffix = $name_suffix, bdate = $bdate
            */
            $seqno = DB::select('seqno')->where('fname', '=', $fname)
                    ->where('mname', '=', $mname)
                    ->where('lname', '=', $lname)
                    ->where('name_suffix', '=', $name_suffix)
                    ->where('bdate', '=', $bdate)
                    ->first();
            \Log::info($seqno);
            

            // UPDATE PRE-SCREENED DONOR TABLE
            $pre_screened_donor = PreScreenedDonor::where('id', $id)->first();
            $pre_screened_donor->donor_sn = $seqno;
            $pre_screened_donor->approval_status = 1;
            $pre_screened_donor->save();
            \Log::info($pre_screened_donor);

            return response()->json([
                'message' => 'This donor already exists. Can only update the pre-screened donor details',
                'status' => 0
            ], 200);
        }
        

    }
}

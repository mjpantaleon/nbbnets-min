<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donor;
use Session;
use App\Donation;

use DB;

class DonorController extends Controller
{
    public function index(){
        $donors = Donor::orderBy('created_dt', 'desc')->get();
        return $donors;  
    }

    public function donorDetails($id){
        // $donor = Donor::with('')
                    // ->where('seqno', $id)->first();
        $donor = Donor::where('seqno', $id)
                    ->with('igg_result')
                    ->with('hla_hna_result')
                    ->first();
        return $donor;
    }

    public function donorHistory($id){
        // SELECT * FROM `donation` WHERE `donor_sn` = '$id'
        $sql = "    SELECT rf.`facility_name`, d.`created_dt`, d.`donation_stat`, d.`mh_pe_stat`, d.`mh_pe_deferral`  
                    FROM `donation` d 
                    LEFT JOIN `r_facility` rf ON d.facility_cd = rf.facility_cd
                    WHERE d.`donor_sn` = $id 
                    AND d.`created_dt` IS NOT NULL ";
        $donor_history = DB::select($sql);
        $donor_history = json_decode(json_encode($donor_history), true);

        return $donor_history;
        \Log::info($donor_history);
    }

    public function search(Request $request){
        $data = $request->except('_token');
        
        $fname = $data['fname'];
        $mname = $data['mname'];
        $lname = $data['lname'];

        // If firstname is not empty OR middlename is nor empty OR lastname then
        if( ($fname != '') || ($mname != '') || ($lname != '') ){
            // check database where firstname is equals to data['fname]
            $query = Donor::query();
            if(!empty($fname)){
                $query = $query->where('fname', 'LIKE', '%'.$fname.'%');
            }

            if(!empty($mname)){
                $query = $query->where('mname', 'LIKE', '%'.$mname.'%');
            }

            // check database where lname is equals to data['lname]
            if(!empty($lname)){
                $query = $query->where('lname', 'LIKE', '%'.$lname.'%');
            }
            // order by created_dt in descending order
            $query = $query->orderBY('created_dt', 'desc');
            // get the first record found
            $keyword = $query->get();
            // return a response
            return response()->json($keyword);
            // return response()->json(["keyword" => $keyword]);
            \Log::info($keyword);
        }
    } /* search */


    public function create(Request $request){
        $data = $request->except('_token');

        // GET THE USER INFO
        $session = Session::get('userInfo');
        $facility_user = Session::get('userInfo')['user_id'];
        $facility_cd = Session::get('userInfo')['facility_cd'];

        // initialize data
        $facility_user = $facility_user;
        $facility_cd = $facility_cd;

        $year_now = date('Y');          // 2020
        $donors_count = Donor::count(); 
        $donors_count = $donors_count + 1;

        $seqno = $facility_cd . $year_now . sprintf("%07d", $donors_count); // 130062020000004

        // initialize data
        $fname = strtoupper($data['fname']);
        $mname = strtoupper($data['mname']);
        $lname = strtoupper($data['lname']);
        $name_suffix = strtoupper($data['name_suffix']);
        $gender = $data['gender'];
        $bdate = $data['bdate'];
        $civil_stat = $data['civil_stat'];
        $occupation = strtoupper($data['occupation']);
        $nationality = $data['nationality'];
        $tel_no = $data['tel_no'];
        $mobile_no = $data['mobile_no'];
        $email = $data['email'];
        $created_dt = date('Y-m-d H:i:s');
        $created_by = $facility_user;

        $request->validate([
            'fname' => 'required|min:2|max:50',
            'lname' => 'required|min:2|max:50',
            'bdate' => 'required|'
        ]);

        // CHECK FIRST IF DONOR ALREADY EXIST
        $check_donor = Donor::where('fname', '=', $fname)
                        ->where('mname', '=', $mname)
                        ->where('lname', '=', $lname)
                        ->where('name_suffix', '=', $name_suffix)
                        ->where('bdate', '=', $bdate)
                        ->first();
        
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

            return response()->json([
                'message' => 'New Donor has been added successfully.',
                'status' => 1
            ], 200);

        } else{
            return response()->json([
                'message' => 'This donor already exists. Please avoid duplication of Donors.',
                'status' => 0
            ], 200);
        }
    } /* create new donor */
}

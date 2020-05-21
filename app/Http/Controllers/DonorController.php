<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donor;

use Illuminate\Support\Facades\DB;

class DonorController extends Controller
{
    public function index(){
        $donors = Donor::orderBy('created_dt', 'desc')->get();
        return $donors;  
    }

    public function donorDetails($id){
        $donor = Donor::where('seqno', $id)->first();
        return $donor;
    }

    public function getAge($id){
        $bdate = Donor::select('bdate')->where('seqno', $id)->first();
        
        $age = \Carbon::parse($bdate)->age;
        return $age;
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
            \Log::info($keyword);
        }
    } /* search */


    public function create(Request $request){
        $data = $request->except('_token');

        // GENERATE DONOR SEQUENCE NUMBER FORMAT: 130062020000001
        $facility_user = '13006_mj';
        $facility_id = 13001;
        $year_now = date('Y');          // 2020
        $donors_count = Donor::count(); 
        $donors_count = $donors_count + 1;

        $seqno = $facility_id . $year_now . sprintf("%06d", $donors_count); // 130062020000004

        // initialize data
        $fname = $data['fname'];
        $mname = $data['mname'];
        $lname = $data['lname'];
        $name_suffix = $data['name_suffix'];
        $gender = $data['gender'];
        $bdate = $data['bdate'];
        $civil_stat = $data['civil_stat'];
        $occupation = $data['occupation'];
        $nationality = $data['nationality'];
        $tel_no = $data['tel_no'];
        $mobile_no = $data['mobile_no'];
        $email = $data['email'];
        $donation_stat = 'A';
        $donor_stat = 'Y';
        $created_dt = date('Y-m-d H:i:s');
        $created_by = $facility_user;

        $created_user = new Donor;
        $created_user->seqno = $seqno;
        $created_user->fname = $fname;
        $created_user->mname = $mname;
        $created_user->lname = $lname;
        $created_user->name_suffix = $name_suffix;
        $created_user->gender = $gender;
        $created_user->bdate = $bdate;
        $created_user->civil_stat = $civil_stat;
        $created_user->occupation = $occupation;
        $created_user->nationality = $nationality;
        $created_user->tel_no = $tel_no;
        $created_user->mobile_no = $mobile_no;
        $created_user->email = $email;
        $created_user->donation_stat = $donation_stat;
        $created_user->donor_stat = $donor_stat;
        $created_user->created_dt = $created_dt;
        $created_user->created_by = $created_by;
        $created_user->save();

        return "OK";


        \Log::info($id);


    }
}

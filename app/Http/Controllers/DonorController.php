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
        $facility_cd = 13001;

        $year_now = date('Y');          // 2020
        $donors_count = Donor::count(); 
        $donors_count = $donors_count + 1;

        $seqno = $facility_cd . $year_now . sprintf("%06d", $donors_count); // 130062020000004

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
        // $donation_stat = 'Y';
        $donor_stat = 'A';
        $created_dt = date('Y-m-d H:i:s');
        $created_by = $facility_user;

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
        // $donor->donation_stat = $donation_stat; // Y
        $donor->donor_stat = $donor_stat;       // *for now use status
        $donor->facility_cd = $facility_cd;
        $donor->created_dt = $created_dt;
        $donor->created_by = $created_by;
        $donor->save();

        return "OK";
        \Log::info($id);
    } /* create new donor */

}

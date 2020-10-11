<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\PreScreenedDonor;
use App\IggResult;
use DB;

use App\Donor;
use Session;
use Carbon;

class PreScreenedDonorController extends Controller
{
    public function index(){
        // GET THE USER INFO
        // $session = Session::get('userInfo');
        // $facility_cd = Session::get('userInfo')['facility_cd'];

        /*  SELECT id, donor_sn, first_name, middle_name, last_name, name_suffix, gender, 
            bdate, address, created_dt, status
            FROM `pre_screened_donors`
            WHERE `created_by` LIKE '000000' 
            ORDER BY `created_dt` DESC 
        */
        $query = "  SELECT id, donor_sn, first_name, middle_name, last_name, name_suffix, gender, 
                    bdate, address, created_dt, approval_dt, status
                    FROM `pre_screened_donors`
                    ORDER BY `created_dt` DESC ";
        $pre_screened_donors = DB::select($query);

        // \Log::info($pre_screened_donors);
        // $candidates = Candidate::orderBy('screen_dt', 'desc')->get();
        return $pre_screened_donors;
    }

    public function search(Request $request){
        $data = $request->except('_token');
        
        $fname = $data['first_name'];
        $mname = $data['middle_name'];
        $lname = $data['last_name'];

        // If firstname is not empty OR middlename is nor empty OR lastname then
        if( ($fname != '') || ($mname != '') || ($lname != '') ){
            // check database where firstname is equals to data['fname]
            $query = PreScreenedDonor::query();
            if(!empty($fname)){
                $query = $query->where('first_name', 'LIKE', '%'.$fname.'%');
            }

            if(!empty($mname)){
                $query = $query->where('middle_name', 'LIKE', '%'.$mname.'%');
            }

            // check database where lname is equals to data['lname]
            if(!empty($lname)){
                $query = $query->where('last_name', 'LIKE', '%'.$lname.'%');
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


    public function getDetails($id){
        $pre_screened_donor = PreScreenedDonor::where('id', $id)->first();
        // \Log::info($pre_screened_donor);
        return $pre_screened_donor;
    }

    // FOR TESTING ENTRIES
    public function getList(){
        // GET THE USER INFO
        $session = Session::get('userInfo');
        $facility_cd = Session::get('userInfo')['facility_cd'];

        // $for_testing_list = PreScreenedDonor::where('status', '!=', '0')
        //                             ->where('facility_cd', 'LIKE', $facility_cd)->get();

        $query = "  SELECT id, donor_sn, first_name, middle_name, last_name, name_suffix, gender, 
                    bdate, address, created_dt, status
                    FROM `pre_screened_donors`
                    WHERE `facility_cd` LIKE $facility_cd AND `status` != '0' 
                    ORDER BY `created_dt` DESC ";
        $for_testing_list = DB::select($query);

        \Log::info($for_testing_list);
        return $for_testing_list;
    }

    public function getApprovedDonorList(Request $request){
        // GET THE USER INFO
        $session = Session::get('userInfo');
        $facility_cd = Session::get('userInfo')['facility_cd'];

        $from = date($request['date_from']);
        $to = date($request['date_to']);

        // SELECT donor_sn, last_name, first_name, middle_name, name_suffix FROM pre_screened_donors WHERE facility_cd LIKE $facility_cd AND status = 1 AND approval_dt BETWEEN $from and $to
        $query = "  SELECT donor_sn, last_name, first_name, middle_name, name_suffix
                    FROM `pre_screened_donors`
                    WHERE `facility_cd` LIKE $facility_cd 
                    AND `status` = '1' 
                    AND `approval_dt` BETWEEN '$from' AND '$to'
                    ORDER BY `approval_dt` ASC "; 
        $approved_donor_list = DB::select($query);
        
        $approved_donor_list = json_decode(json_encode($approved_donor_list), true);

        if($approved_donor_list){

            for ($i=0; $i < count($approved_donor_list); $i++) { 
                $ids[$i]['first_name'] = $approved_donor_list[$i]['first_name'];
                $ids[$i]['middle_name'] = $approved_donor_list[$i]['middle_name'];
                $ids[$i]['last_name'] = $approved_donor_list[$i]['last_name'];
                $ids[$i]['name_suffix'] = $approved_donor_list[$i]['name_suffix'];
                $ids[$i]['donor_sn'] = $approved_donor_list[$i]['donor_sn'];
                $ids[$i]['donation_id'] = "";
                $ids[$i]['HBSAG'] = "";
                $ids[$i]['HCV'] = "";
                $ids[$i]['HIV'] = "";
                $ids[$i]['MALA'] = "";
                $ids[$i]['RPR'] = "";
            }

            \Log::info(gettype($ids));

            return $ids;
            // \Log::info($approved_donor_list);
            // return $approved_donor_list;
            
        } else{
            return false;
        }
        
        // \Log::info($approved_donor_list);
        // return $approved_donor_list;
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
            $pre_screened_donor->facility_cd = $facility_cd;
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
                    ->where('fname', '=', $first_name)
                    ->where('mname', '=', $middle_name)
                    ->where('lname', '=', $last_name)
                    ->where('name_suffix', '=', $name_suffix)
                    ->where('bdate', '=', $bdate)
                    ->first();
            // \Log::info($seqno);

            // UPDATE PRE-SCREENED DONOR TABLE
            $pre_screened_donor = PreScreenedDonor::where('id', $id)->first();
            $pre_screened_donor->donor_sn = $seqno->seqno;
            $pre_screened_donor->facility_cd = $facility_cd;
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

        $facility_cd = $facility_cd;
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
            $pre_screened_donor->name_suffix = $name_suffix ? $name_suffix : "";
            
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
            $pre_screened_donor->second_answer = $test_results != null ? json_encode($test_results) : "";
            $pre_screened_donor->not_sure_answer = $symptoms != null ? json_encode($symptoms) : "";
            // $pre_screened_donor->second_answer = implode(',', $test_results);
            // $pre_screened_donor->not_sure_answer = implode(',', $symptoms);

            $pre_screened_donor->facility_cd = $facility_cd;
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


    // IGG
    public function getDonorsForIgg(Request $request){
        $ids            = [];
        $from           = date($request['date_from']);
        $to             = date($request['date_to']);
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

        /*
        SELECT ps.donor_sn, ps.last_name, ps.first_name, ps.middle_name, ps.name_suffix
        FROM pre_screened_donors ps
        LEFT JOIN igg_results ig ON ig.donor_sn = ps.donor_sn
        WHERE ps.approval_dt BETWEEN '2020-07-01' AND '2020-10-17'
        AND ps.facility_cd = '13109'
        AND ps.status = '1'
        AND ig.donation_id IS NULL
        */

        $query = "  SELECT ps.donor_sn, ps.last_name, ps.first_name, ps.middle_name, ps.name_suffix
                    FROM pre_screened_donors ps
                    LEFT JOIN igg_results ig ON ig.donor_sn = ps.donor_sn
                    WHERE ps.approval_dt BETWEEN '$from' AND '$to'
                    AND ps.facility_cd = '$facility_cd'
                    AND ps.status = '1'
                    AND ig.donation_id IS NULL ";
                
        $donors_to_igg = DB::select($query);

        \Log::info($donors_to_igg);
        
        $donors_to_igg = json_decode(json_encode($donors_to_igg), true);
        // return($donors_to_igg);

        if($donors_to_igg){
            for($i = 0; $i < count($donors_to_igg); $i++){
                $ids[$i]['donor_sn'] = $donors_to_igg[$i]['donor_sn'];
                $ids[$i]['last_name'] = $donors_to_igg[$i]['last_name'];
                $ids[$i]['first_name'] = $donors_to_igg[$i]['first_name'];
                $ids[$i]['middle_name'] = $donors_to_igg[$i]['middle_name'];
                $ids[$i]['name_suffix'] = $donors_to_igg[$i]['name_suffix'];
                $ids[$i]['donation_id'] = "";
                $ids[$i]['cut_off_val'] = "";
                $ids[$i]['igg_result'] = "";
            }
            
            \Log::info($ids);
            return $ids;
        } else {
            return false;
        }
    }


    public function saveIggResult(Request $request){
        $facility_user  = Session::get('userInfo')['user_id'];
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

        $verifier       = $request->get('verifier');

        
        $igg_results  = $request->get('igg_results');
        \Log::info($igg_results);
        
        $duplicated_id = '';

        // EXTRACT DATA
        foreach($igg_results as $i => $ig){
            
            // FOR NON EMPTY FIELDS
            if($ig['igg_result']){
                $donor_sn = $ig['donor_sn'];
                $donation_id = $ig['donation_id'];
                $cut_off_val = $ig['cut_off_val'];
                $igg_result = $ig['igg_result'];

                // CHECK DONATION ID IF ALREADY EXIST AT `igg_results` table
                $check_donation_id = IggResult::where('donation_id', '=',  $donation_id)->first();

                // IF DONATION ID DOES NOT EXIST
                if($check_donation_id === null){

                    // THEN INSERT NEW 
                    $igg = new  IggResult;
                    $igg->donor_sn = $donor_sn;
                    $igg->donation_id = $donation_id;
                    $igg->cut_off_val = $cut_off_val;
                    $igg->igg = $igg_result;

                    $igg->result_by = $facility_user;
                    $igg->result_dt = date("Y-m-d H:i:s");
                    $igg->approved_by = $verifier;
                    $igg->approval_dt = date("Y-m-d H:i:s");
                    $igg->save();
                }

                // ELSE IF DONATION ALREADY EXIST
                else{

                    // LIST DOWN DUPLICATED DONATION ID
                    $duplicated_id = $duplicated_id.' '.$ig['donation_id'];
                }
            } /* if($ig) */

        } /* end foreach */

        // if donation id already exist then 
        if($duplicated_id){
            
            // send this response
            return response()->json([
                'message' => "This donation IDs already exist: \n $duplicated_id",
                'status' => 1
            ], 200);
        }

        // else proceed to save record
        else{
            return response()->json([
                'message' => 'IgG result/s has been saved.',
                'status' => 1
            ], 200);
        }

    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Donation;
use Session;
use DB;

class DonationController extends Controller
{
    public function index(Request $request){
        $data = $request->except('_token');

        \Log::info($data);

        $donation_dt = $data['donation_dt'];

        // SELECT * FROM donations 
        // LEFT JOIN donors ON donations.donor_sn = donor.seqno
        // WHERE created_dt = '$donation_dt'
        // ORDER by created_dt DESC
        $query = "  SELECT d.donation_type, d.mh_pe_stat, d.collection_method, d.collection_stat,  d.donation_id,  
                    dd.fname, dd.mname, dd.lname
                    FROM donation d
                    LEFT JOIN donor dd ON d.donor_sn = dd.seqno 
                    WHERE d.created_dt = '$donation_dt' AND d.sched_id = 'Walk-in'
                    ORDER by d.created_dt DESC ";
        $donations = DB::select($query);
        
        \Log::info($donations);
        return response()->json($donations);  
    }

    public function create(Request $request){
        $data = $request->except('_token');

        // GET THE USER INFO
        $session = Session::get('userInfo');
        $facility_user = Session::get('userInfo')['user_id'];
        $facility_cd = Session::get('userInfo')['facility_cd'];
        $verifier    = $request->get('verifier');

        // initialize data
        // $facility_user = $facility_user;
        // $facility_cd = $facility_cd;
        
        // GENERATE A SEQNO BASED ON FACILITY CODE.YEAR.6DIGIT'
        // $year_now = date('Y');
        // $donation_count = Donation::count();
        // $donation_count = $donation_count + 1;
        // $seqno = $facility_cd.$year_now. sprintf("%06d", $donation_count); //130012020000001
        $seqno = Donation::generateSeqno($facility_cd);

        // initialize data
        $donation_id = $data['donation_id'];
        $donor_sn = $data['donor_sn'];
        $sched_id = 'Walk-in';
        $pre_registered = 'N';
        $donation_type = $data['donation_type'];            // Autologous, Voluntary, Fam/Replacement or Paid
        $collection_method = $data['collection_method'];    // WB = Whole Blood, AP = Apheresis

        // \Log::info($mh_pe_deferral);
        // this section updates when donor is temporary, permanently or indefinitely deferred
        $hemoglobin = $data['hemoglobin'];
        $body_weight = $data['body_weight'];
        $blood_pressure = $data['blood_pressure'];
        $pulse_rate = $data['pulse_rate'];
        $temperature = $data['temperature'];

        // push all PE selection to an array using array_push
        $mh_pe_deferral = array();
        array_push($mh_pe_deferral, $hemoglobin, $body_weight, $blood_pressure, $pulse_rate, $temperature);
        
        // check if PE selection is empty
    
        // convert pushed array to string separated by ,
        $mh_pe_deferral = $mh_pe_deferral != null ? json_encode($mh_pe_deferral) : '';
        // $mh_pe_deferral = implode(',',$mh_pe_deferral);
        $mh_pe_question = $data['mh_pe_question'] != null ? json_encode($data['mh_pe_question']) : '';  // MH CHOICE/S *use implode to convert array to string
        // $mh_pe_question = implode(',', $data['mh_pe_question']);  // MH CHOICE/S *use implode to convert array to string
        $mh_pe_remark = $data['mh_pe_remark'];      // OPTIONAL
        $mh_pe_stat = $data['mh_pe_stat'];          // A, TD, PD, ID

        $collection_stat = $data['collection_stat'];
        // this section updates when collection status is not equals to COL = Collected
        $coluns_res = $data['coluns_res'];          // BULGE, FAINT, CLOT

        $created_by = $facility_user;
        $created_dt = $data['created_dt'];
        $approved_by = $verifier;
        $updated_dt = date('Y-m-d H:i:s');

        // CHECK IF DONATION ID ALREADY EXIST
        // check first if donationID already exists
        $check_donation_id = Donation::where('donation_id', '=', $donation_id)->first();

        // SAVE RECORD
        if($check_donation_id === null){
            $donation = new Donation;
            $donation->seqno = $seqno;
            $donation->donation_id = $donation_id;
            $donation->donor_sn = $donor_sn;
            $donation->sched_id = $sched_id;
            $donation->pre_registered = $pre_registered;
            $donation->donation_type = $donation_type;
            $donation->collection_method = $collection_method;
            $donation->facility_cd = $facility_cd;
            
            $donation->mh_pe_deferral = $mh_pe_deferral;
            $donation->mh_pe_question = $mh_pe_question;
            $donation->mh_pe_remark = $mh_pe_remark;
            $donation->mh_pe_stat = $mh_pe_stat;            
            
            $donation->collection_stat = $collection_stat;
            $donation->coluns_res = $coluns_res;
    
            $donation->created_by = $created_by;
            $donation->created_dt = $created_dt;
            $donation->approved_by = $approved_by;
            $donation->save();
    
            return response()->json([
                'message' => 'New Donation has been added successfully.',
                'status' => 1
            ], 200);
            \Log::info($id);
        }
        else{
            // return response('This donation ID already exists!', 200);
            return response()->json([
                'message' => 'This donation ID already exists!',
                'status' => 0
            ], 200);
        } 
    }

}

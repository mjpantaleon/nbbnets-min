<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Donation;
use App\Donor;
use App\Component;
use App\RCpComponentCode;
use App\Helpers\ComputeExpiry;
use Session;
use DB;

use App\AdditionalHlaHnaTest;
use App\IggResult;
use App\Testing;


class DonationController extends Controller
{
    public function index(Request $request){
        // GET THE USER INFO
        $session = Session::get('userInfo');
        $facility_cd = Session::get('userInfo')['facility_cd'];

        $data = $request->except('_token');

        \Log::info($data);

        $donation_dt = $data['donation_dt'];

        $query = "  SELECT d.donation_type, d.mh_pe_stat, d.collection_method, d.collection_stat,  d.donation_id,  d.facility_cd,
                    dd.fname, dd.mname, dd.lname
                    FROM donation d
                    LEFT JOIN donor dd ON dd.seqno = d.donor_sn 
                    WHERE d.created_dt like '%$donation_dt%' 
                    AND d.sched_id = 'Walk-in' 
                    AND d.collection_stat = 'COL'
                    AND d.facility_cd LIKE '%$facility_cd%'
                    AND dd.donor_stat IS NOT NULL
                    ORDER by d.created_dt DESC ";

        $donations = DB::select($query);
        $donations = json_decode(json_encode($donations), true);


        
        \Log::info($donations);
        return $donations;  
    }

    // public function create()
    public function create(Request $request){
        $data = $request->except('_token');

        // GET THE USER INFO
        $session = Session::get('userInfo');
        $facility_user = Session::get('userInfo')['user_id'];
        $facility_cd = Session::get('userInfo')['facility_cd'];
        $verifier    = $request->get('verifier');

        // initialize data
        $donation_id = $data['donation_id'];
        $donor_sn = $data['donor_sn'];
        $sched_id = 'Walk-in';
        $pre_registered = 'N';
        $donation_type = $data['donation_type'];            // Autologous, Voluntary, Fam/Replacement or Paid
        $collection_method = $data['collection_method'];    // WB = Whole Blood, P = Pheresis

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
    
        // convert pushed array to string separated by ,
        $mh_pe_deferral = $mh_pe_deferral != null ? json_encode($mh_pe_deferral) : '';
        // $mh_pe_deferral = implode(',',$mh_pe_deferral);
        $mh_pe_question = $data['mh_pe_question'] != null ? json_encode($data['mh_pe_question']) : '';  // MH CHOICE/S *use implode to convert array to string
        // $mh_pe_question = implode(',', $data['mh_pe_question']);  // MH CHOICE/S *use implode to convert array to string
        $mh_pe_remark = $data['mh_pe_remark'];      // * from other reason
        $mh_pe_stat = $data['mh_pe_stat'];          // A, TD, PD, ID

        $collection_stat = $data['collection_stat'];
        // this section updates when collection status is not equals to COL = Collected
        $coluns_res = $data['coluns_res'];          // BULGE, FAINT, CLOT

        $created_by = $facility_user;
        $created_dt = $data['created_dt'];
        $approved_by = $verifier;
        $updated_dt = date('Y-m-d H:i:s');


        /*
            CHECK FIRST IF THERE IS A DONATION ID
        */
        if($donation_id){
        \Log::info($donation_id);

            // CHECK AT TESTING IF THIS DONATION ID EXISTS
            $check_tests = Testing::where('donation_id', '=', $donation_id)
                        ->first();
            \Log::info($check_tests);

            // if donation_id have match then
            if($check_tests){

                // CHECK IF DONATION ID AND DONOR ALREADY EXIST
                $check_donation_details = Donation::where('donation_id', '=', $donation_id)
                                ->where('donor_sn', '=', $donor_sn)
                                ->first();
                \Log::info($check_donation_details);
                
                // IF DONATION ID HAVE NO MATCH THEN
                if($check_donation_details === null){
                    
                    // CHECK FOR DONATION ID AND DONOR IF MATCHED USING IGG
                    $check_if_match = IggResult::where('donation_id', $donation_id)
                                    ->where('donor_sn', $donor_sn)
                                    ->first();
                    \Log::info($check_if_match);

                    // if matched then 
                    if($check_if_match){

                        // proceed saving donation
                        $seqno = Donation::generateSeqno($facility_cd);

                        $d = new Donation;
                        $d->seqno = $seqno; 
                        $d->donation_id = $donation_id; 

                        $d->donor_sn = $donor_sn;
                        $d->sched_id = "Walk-in";
                        $d->pre_registered = "Y";
                        $d->donation_type = $donation_type;         
                        $d->collection_method = $collection_method;     /* if P or WB */
                        $d->donation_stat = "Y";
                        $d->facility_cd = $facility_cd;

                        if($d->collection_method == 'WB'){
                            $d->blood_bag = $request->get('blood_bag');
                            $d->collection_type = "CPC19";
                        }

                        $d->mh_pe_deferral = $mh_pe_deferral;
                        $d->mh_pe_question = $mh_pe_question;
                        $d->mh_pe_remark = $mh_pe_remark;
                        $d->mh_pe_stat = $mh_pe_stat;            

                        $d->collection_stat = $collection_stat;
                        $d->coluns_res = $coluns_res;

                        $d->created_by = $created_by;
                        $d->created_dt = $created_dt;
                        $d->approved_by = $approved_by;
                        $d->save();


                        // UPDATE `pre_screened_donors` table
                        Donor::where('seqno', $donor_sn)
                            ->update(['donation_stat' => 'Y', 'donor_stat' => 'A']);

                        return response()->json([
                            'message' => 'Donation has been successfully updated.',
                            'status' => 1
                        ], 200);
                        
                    } else {

                        return response()->json([
                            'message' => 'Donor and Donation ID do not match.',
                            'status' => 0
                        ], 200);
                        
                    }

                } 
                // IF DONATION ID HAVE NO MATCH THEN

                // IF DONATION ID HAVE MATCH
                else{

                    return response()->json([
                    'message' => "This Donation ID already exist: \n $donation_id",
                    'status' => 0
                    ], 200);
                } 
                // IF DONATION ID DOESNT MATCH
            
            } /* check_tests */

            else {

                return response()->json([
                'message' => "This Donation ID have not been Tested yet: \n $donation_id",
                'status' => 0
                ], 200);

            }
        
        } 
        /*
            Reason for no Donation ID is when the donor is either Temporary deferred (TD), Permanent deferred (PD) or Indefinite deferred (ID)
        */
        else{

            $seqno = Donation::generateSeqno($facility_cd);

            $d = new Donation;
            $d->seqno = $seqno;
            $d->donor_sn = $donor_sn;
            $d->sched_id = "Walk-in";
            $d->pre_registered = "Y";
            $d->donation_type = $donation_type;
            $d->collection_method = $collection_method; /* if P or WB */
            $d->donation_stat = "Y";
            $d->facility_cd = $facility_cd;

            if($collection_method == 'WB'){
                $d->blood_bag = $request->get('blood_bag');
                $d->collection_type = "CPC19";
            }

            $d->mh_pe_deferral = $mh_pe_deferral;
            $d->mh_pe_question = $mh_pe_question;
            $d->mh_pe_remark = $mh_pe_remark;
            $d->mh_pe_stat = $mh_pe_stat;            
            
            $d->collection_stat = $collection_stat;
            $d->coluns_res = $coluns_res;
    
            $d->created_by = $created_by;
            $d->created_dt = $created_dt;
            $d->approved_by = $approved_by;
            $d->save();
            
            // // UPDATE `pre_screened_donors` table
            // Donor::where('seqno', $donor_sn)
            //     ->update(['donation_stat' => 'Y', 'donor_stat' => 'A']);

            return response()->json([
                'message' => 'Donation has been successfully updated.',
                'status' => 1
            ], 200);
            \Log::info($id);
        }


    } // public function create()

    
}

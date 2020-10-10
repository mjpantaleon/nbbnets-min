<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;
use App\Testing;
use App\TestingDetails;
use App\Donation;
use App\PreScreenedDonor;
use App\Exam;
use App\Donor;

class TestingDetailsController extends Controller
{
    public function getDonationId(Request $request){
        $ids            = [];
        $from           = date($request['date_from']);
        $to             = date($request['date_to']);
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

        $sched_id       = 'Walk-in';
        $col_stat       = 'COL';

        /*
        SELECT t1.donation_id
        FROM donation t1
        LEFT JOIN bloodtest_dtls t2 ON t1.donation_id = t2.donation_id
        WHERE t2.donation_id IS NULL
        AND t1.donation_id IS NOT NULL
        AND t1.facility_cd = '11001'
        AND t1.sched_id = 'Walk-in'
        AND t1.created_dt 
        AND t1.created_dt BETWEEN '2020-01-01' AND '2020-06-17'
        AND t1.collection_stat = 'COL'
        */

         $sql = "   SELECT t1.donation_id
                    FROM donation t1
                    LEFT JOIN bloodtest_dtls t2 ON t1.donation_id = t2.donation_id
                    WHERE t2.donation_id IS NULL
                    AND t1.donation_id IS NOT NULL
                    AND t1.facility_cd = '$facility_cd'
                    AND t1.sched_id = '$sched_id'
                    AND t1.created_dt BETWEEN '$from' AND '$to'
                    AND t1.collection_stat = '$col_stat' ";

        $donation = DB::select($sql);
        \Log::info($donation);

        $donation = json_decode(json_encode($donation), true);

        if($donation){

            foreach($donation as $key => $val){
                // $donation[$key]['count_key'] = $key;
                $ids[$val['donation_id']]['donation_id'] = $val['donation_id'];
                $ids[$val['donation_id']]['HBSAG'] = "";
                $ids[$val['donation_id']]['HCV'] = "";
                $ids[$val['donation_id']]['HIV'] = "";
                $ids[$val['donation_id']]['MALA'] = "";
                $ids[$val['donation_id']]['RPR'] = "";
            }

            return $ids;
            \Log::info($ids);
            
        } else{
            return false;
        }
    } // getDonationId

    public function addResult(Request $request, $id){
        $facility_user  = Session::get('userInfo')['user_id'];
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

        $donor_sn       = $request->get('donor_sn');
        $verifier       = $request->get('verifier');
        $sched_id       = 'Walk-in';
        
        $exams = Exam::whereDisableFlg('N')->pluck('exam_name','exam_cd');
        // \Log::info($exams);

        $donation_id    = $request['donation_id'];
        $TTI = [
            'HBSAG'          => $request['HBSAG'],
            'HCV'            => $request['HCV'],
            'HIV'            => $request['HIV'],
            'MALA'           => $request['MALA'],
            'RPR'            => $request['RPR']
        ];
        
        $bloodtest_no = Testing::generateNo($facility_cd);
        \Log::info($bloodtest_no);

        // check first if donationID already exists in bloodtest_dtls table and donation table
        $check_donation_id = TestingDetails::where('donation_id', '=', $donation_id)->first();
        
        if($check_donation_id === null){
            foreach($TTI as $key => $value){
                // INSERT RECORD AT `bloodtest_dtls` table
                $t2 = new TestingDetails;
                $t2->bloodtest_no = $bloodtest_no;
                $t2->donation_id = $donation_id;
                $t2->exam_cd = $key;
                $t2->result_int = $value == 'n' ? 'n' : 'r';
                $t2->created_by = $facility_user;
                $t2->created_dt = date('Y-m-d H:i:s');
                $t2->save();
            }

            // INSET RECORD AT `bloodtest` table
            $t = new Testing;
            $t->facility_cd = $facility_cd;
            $t->bloodtest_no = $bloodtest_no;
            $t->bloodtest_dt = date('Y-m-d H:i:s');
            $t->donation_id = $donation_id;

            // checking for 'R' values
            $fail = 0;
            foreach($TTI as $key => $value){
                if(strtoupper($value) == 'R'){
                    \Log::info($key);
                    $fail++;
                }
            }

            $t->result = $fail ? 'R' : 'N';
            $t->created_by = $facility_user;
            $t->created_dt = date('Y-m-d H:i:s');
            $t->updated_by = $verifier;
            $t->updated_dt = date('Y-m-d H:i:s');
            $t->save();

            // UPDATE `pre_screened_donors` table
            PreScreenedDonor::where('donor_sn', $donor_sn)
                            ->update(['status' => '2']);

            // INSERT record at `donation` table
            $seqno = Donation::generateSeqno($facility_cd);
            $d = new Donation;
            $d->seqno = $seqno;
            $d->donation_id = $donation_id;
            $d->donor_sn = $donor_sn;
            $d->pre_registered = 'Y';
            $d->sched_id = $sched_id;
            $d->donation_stat = $fail ? 'REA' : 'Y';
            $d->facility_cd = $facility_cd;
            // $d->created_dt = date('Y-m-d H:i:s');
            $d->save();

            //Update 'Donor' table
            $donor_update_arr = array(
                'donation_stat' => $fail ? 'N' : 'Y',
                'donor_stat' => $fail ? 'PD' : 'A'                
            );

            $stat = Donor::where('seqno', $donor_sn)
                            ->update($donor_update_arr);

            return response()->json([
                'message' => 'Testing Details has been saved.',
                'status' => 1
            ], 200);
        }
        else{
            
            // return response('This donation ID already exists!', 200);
            return response()->json([
                'message' => 'This donation ID already exists!',
                'status' => 0
            ], 200);
        }
    }


    public function save(Request $request){
        $facility_user  = Session::get('userInfo')['user_id'];
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

        $verifier       = $request->get('verifier');
        $sched_id       = 'Walk-in';

        $blood_testing  = $request->get('blood_testing');
        \Log::info($blood_testing);
        // return $blood_testing;

        \Log::info(count($blood_testing));

        $duplicated_id = '';

        // LOOP EACH RECORD SENT FROM TTI RESULT VUE
        foreach($blood_testing as $k => $bt){

            // CHECK IF THERE'S DONATION ID
            if($bt['donation_id']){

                // GENERATE 
                $bloodtest_no = Testing::generateNo($facility_cd);
                \Log::info($bloodtest_no);
    
                $donation_id = $bt['donation_id'];
                $donor_sn = $bt['donor_sn'];
                
                $TTI = [
                    'HBSAG'          => $bt['HBSAG'],
                    'HCV'            => $bt['HCV'],
                    'HIV'            => $bt['HIV'],
                    'MALA'           => $bt['MALA'],
                    'RPR'            => $bt['RPR']
                ];
                
                // check first if donationID already exists in bloodtest_dtls table and donation table
                $check_donation_id = TestingDetails::where('donation_id', '=', $donation_id)->first();
                
                // if donation id has no duplicate then
                if($check_donation_id === null){
                    
                    // loop each record
                    foreach($TTI as $key => $value){
    
                        // INSERT RECORD AT `bloodtest_dtls` table
                        $t2 = new TestingDetails;
                        $t2->bloodtest_no = $bloodtest_no;
                        $t2->donation_id = $donation_id;
                        $t2->exam_cd = $key;
                        $t2->result_int = $value == 'n' ? 'n' : 'r';
                        $t2->created_by = $facility_user;
                        $t2->created_dt = date('Y-m-d H:i:s');
                        $t2->save();  
                    }
    
                    // INSET RECORD AT `bloodtest` table
                    $t = new Testing;
                    $t->facility_cd = $facility_cd;
                    $t->bloodtest_no = $bloodtest_no;
                    $t->bloodtest_dt = date('Y-m-d H:i:s');
                    $t->donation_id = $donation_id;
    
                    // checking for 'R' values
                    $fail = 0;
                    foreach($TTI as $key => $value){
                        if(strtoupper($value) == 'R'){
                            \Log::info($key);
                            $fail++;
                        }
                    }
                    
                    $t->result = $fail ? 'R' : 'N';
                    $t->created_by = $facility_user;
                    $t->created_dt = date('Y-m-d H:i:s');
                    $t->updated_by = $verifier;
                    $t->updated_dt = date('Y-m-d H:i:s');
                    $t->save();
    
                    // UPDATE `pre_screened_donors` table
                    PreScreenedDonor::where('donor_sn', $donor_sn)
                                    ->update(['status' => '2']);
    
                    // INSERT record at `donation` table
                    $seqno = Donation::generateSeqno($facility_cd);
                    $d = new Donation;
                    $d->seqno = $seqno;
                    $d->donation_id = $donation_id;
                    $d->donor_sn = $donor_sn;
                    $d->pre_registered = 'Y';
                    $d->sched_id = $sched_id;
                    $d->donation_stat = $fail ? 'REA' : 'Y';
                    $d->facility_cd = $facility_cd;
                    // $d->created_dt = date('Y-m-d H:i:s');
                    $d->save();
    
                    //Update 'Donor' table
                    $donor_update_arr = array(
                        'donation_stat' => $fail ? 'N' : 'Y',
                        'donor_stat' => $fail ? 'PD' : 'A'                
                    );
    
                    $stat = Donor::where('seqno', $donor_sn)
                                    ->update($donor_update_arr);
                }
                
                // if donation id already exist then
                else{

                    // concut/ list down donation ids
                    $duplicated_id = $duplicated_id.' '.$bt['donation_id'];

                }
            }
            // CHECK IF THERE'S DONATION ID
            
        }
        \Log::info($duplicated_id);
        // LOOP EACH RECORD SENT FROM TTI RESULT VUE

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
                'message' => 'Testing result/s has been saved.',
                'status' => 1
            ], 200);
        }

        
    }


    
}

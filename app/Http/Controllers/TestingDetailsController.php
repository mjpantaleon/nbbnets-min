<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;
use App\Testing;
use App\TestingDetails;
use App\Exam;

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

    public function save(Request $request){
        $blood_testing = $request->get('blood_testing');
        $verifier      = $request->get('verifier');

        $facility_user = Session::get('userInfo')['user_id'];
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];
        $exams = Exam::whereDisableFlg('N')->pluck('exam_name','exam_cd');

        foreach($blood_testing as $d){
            $bloodtest_no = Testing::generateNo($facility_cd);
            $t = new Testing;
            $t->facility_cd = $facility_cd;
            $t->bloodtest_no = $bloodtest_no;
            $t->bloodtest_dt = date('Y-m-d H:i:s');
            $t->donation_id = $d['donation_id'];

            $fail = 0;
            foreach($exams as $exam_cd => $exam_name){
                if(strtoupper($d[$exam_cd]) == 'R'){
                    $fail++;
                }
            }

            $t->result = $fail ? 'R' : 'N';
            $t->created_by = $facility_user;
            $t->created_dt = date('Y-m-d H:i:s');
            $t->updated_by = $verifier;

            $t->save();

            foreach($exams as $exam_cd => $exam_name){
                $t2 = new TestingDetails;
                $t2->bloodtest_no = $bloodtest_no;
                $t2->donation_id = $d['donation_id'];
                $t2->exam_cd = $exam_cd;
                $t2->result_int = $d[$exam_cd] == 'r' ? 'r' : 'n';
                $t2->created_by = $facility_user;
                $t2->created_dt = date('Y-m-d H:i:s');
                $t2->save();
            }

            if($fail){
                FlagReactiveController::flagReactive($d['donation_id']);
            }else{
                FlagReactiveController::flagNonReactive($d['donation_id'],$facility_cd);
            }

        }
        return response('Testing Details has been saved.', 200);
    } // save
    
            
            // foreach($blood_testing as $d){
            //     $bloodtest_no = Testing::generateNo($facility_cd);
            //     // \Log::info($d);
    
            //     $fail = 0;
                
            //     foreach($exams as $key => $val){ 
    
            //         // \Log::info($d[$val['exam_cd']]);
            //         if(strtoupper($d[$val['exam_cd']]) == 'R'){
            //             // \Log::info($d[$val['exam_cd']]);
            //             $fail++;
            //         } 
    
            //         $t2 = new TestingDetails;
            //         $t2->bloodtest_no = $bloodtest_no;
            //         $t2->donation_id = $d['donation_id'];
            //         $t2->exam_cd = $val['exam_cd'];
            //         $t2->result_int = $d[$val['exam_cd']] == 'r' ? 'r' : 'n';
            //         $t2->created_by = $facility_user;
            //         $t2->created_dt = date('Y-m-d H:i:s');
            //         $t2->save();
    
            //     }
                
            //     // important 
            //     return response($t2, 200);
    
    
            //     if($fail){
            //         FlagReactiveController::flagReactive($d['donation_id']);
            //     }else{
            //         FlagReactiveController::flagNonReactive($d['donation_id'], $facility_cd);
            //     }
    
            // }

    // private function processSaveArray($data, $blood_testing, $verifier){

    //     $save_array = [];

    //     $donation_ids_data = self::formatDonationIds($blood_testing);

    //     foreach($data as $key => $val){

    //         $donation_id = $val['donation_id'];

    //         $getProcessingData = $donation_ids_data[$donation_id];

    //         if($getProcessingData['HBSAG']){
    //             // $exp = self::getExpiration("80", $val['created_dt']);
    //             $save_array[] = self::formatSaveArray($val, "HBSAG", $exp, $getProcessingData['HBSAG']);
    //         }

    //         if($getProcessingData['HVC']){
    //             // $exp = self::getExpiration("81", $val['created_dt']);
    //             $save_array[] = self::formatSaveArray($val, "HVC", $exp, $getProcessingData['HVC']);
    //         }
            
    //         if($getProcessingData['HIV']){
    //             // $exp = self::getExpiration("82", $val['created_dt']);
    //             $save_array[] = self::formatSaveArray($val, "HIV", $exp, $getProcessingData['HIV']);
    //         }

    //         if($getProcessingData['MALA']){
    //             // $exp = self::getExpiration("83", $val['created_dt']);
    //             $save_array[] = self::formatSaveArray($val, "MALA", $exp, $getProcessingData['MALA']);
    //         }

    //         if($getProcessingData['RPR']){
    //             // $exp = self::getExpiration("84", $val['created_dt']);
    //             $save_array[] = self::formatSaveArray($val, "RPR", $exp, $getProcessingData['RPR']);
    //         }


    //     }

    //     return $save_array;

    // }

    // private function formatDonationIds($data){

    //     $arr = [];

    //     foreach($data as $key => $val){
    //         $arr[$val['donation_id']] = $val;
    //     }

    //     return $arr;
    // }

    // private function formatSaveArray($data, $exams, $exp, $vol){

    //     $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];
    //     $user_id        = Session::get('userInfo')['user_id'];

    //     $year_now = date('Y');              // 2020
    //     $test_count = Testing::count();     // get last inserted digit at testing table
    //     $test_count = $test_count + 1;      // increment by 1
    //     $seqno = $facility_cd . $year_now . sprintf("%07d", $test_count); // 1300620200000004

    //     // IF ANY OF THE TEST RESULTS HAS A POSITIVE VALUE THEN FINAL RESULT IS EQUAL TO R = REACTIVE
    //     $fail = 0;
    //     foreach($exams as $exam_cd => $exam_name){
    //         if($d[$exam_cd] == 'R'){
    //             $fail++;
    //         }
    //     }

    //     return array(
    //         'bloodtest_no'  => $seqno,
    //         'donation_id'   => $data['donation_id'],
    //         'exam_cd'       => $exams,                // IF HBSAG, HCV, HIV, MALA, RPR
    //         'result_int'    => $blood_testing,

    //         'created_by'    => $user_id,
    //         'created_dt'    => date('Y-m-d H:i:s'),
    //     );

    // }

    
}

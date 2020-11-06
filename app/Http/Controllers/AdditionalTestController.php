<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AdditionalTest;
use App\AdditionalHlaHnaTest;
use App\Component;
use Session;
use DB;

class AdditionalTestController extends Controller
{

    // ///////////////////////////////////////////////// NAT ///////////////////////////////////
    /*
    SELECT c.donation_id, c.component_cd, rc.component_abbr
    FROM component c
    LEFT JOIN r_cp_component_codes rc ON c.component_cd = rc.component_code
    LEFT JOIN additionaltest ad ON c.donation_id = ad.donation_id
    WHERE c.created_dt BETWEEN '2020-07-01' AND '2020-10-17'
    AND c.location = '13109'
    AND c.comp_stat = 'FBT'
    AND rc.component_abbr IS NOT NULL 
    AND ad.nat IS NULL AND ad.nat_by IS NULL
    */
    public function getComponentsForNatTest(Request $request){
        $ids            = [];
        $from           = date($request['date_from']);
        $to             = date($request['date_to']);
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];
        
        $sql =" SELECT c.donation_id, c.component_cd, rc.component_abbr
                FROM component c
                LEFT JOIN r_cp_component_codes rc ON c.component_cd = rc.component_code
                LEFT JOIN additionaltest ad ON c.donation_id = ad.donation_id
                WHERE c.created_dt BETWEEN '$from' AND '$to'
                AND c.location = '$facility_cd'
                AND c.comp_stat = 'FBT'
                AND rc.component_abbr IS NOT NULL 
                AND ad.nat IS NULL AND ad.nat_by IS NULL ";

        $nat = DB::select($sql);

        \Log::info($nat);
        
        $nat = json_decode(json_encode($nat), true);
        // return($nat);
        
        if($nat){
            for($i = 0; $i < count($nat); $i++){
                $ids[$i]['donation_id'] = $nat[$i]['donation_id'];
                $ids[$i]['component_abbr'] = $nat[$i]['component_abbr'];
                $ids[$i]['nat_result'] = "";
            }
            
            \Log::info($ids);
            return $ids;
        } else {
            return false;
        }
    } /* getComponentsForNatTest() */


    public function saveNatResult(Request $request){
        $facility_user  = Session::get('userInfo')['user_id'];
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

        $verifier       = $request->get('verifier');
        
        $nat_results  = $request->get('nat_results');
        \Log::info($nat_results);

        // EXTRACT DATA
        foreach($nat_results as $n => $nr){
            
            // FOR NON EMPTY FIELDS
            if($nr['nat_result']){
                
                $donation_id = $nr['donation_id'];
                $nat_result = $nr['nat_result'];
                
                // CHECK IF DONATION ID ALREADY EXIST AT ADDITIONAL TEST TABLE
                $check_donation_id = AdditionalTest::where('donation_id', '=', $donation_id)->first();
                
                // IF DONATION ID DOESNT EXIST THEN
                if($check_donation_id === null){
                    
                    // INSERT IN THE `additionaltest` TABLE
                    $n = new AdditionalTest;
                    $n->donation_id = $donation_id;
                    $n->nat = $nat_result;
                    $n->nat_by = $facility_user;
                    $n->nat_verifier = $verifier;
                    $n->save();

                    // UPDATE `component.comp_stat` FROM `FBT` TO `FBL`, 
                    // * `updated_by` and `updated_dt`
                    // Component::where('donation_id', $donation_id)
                    //         ->where('location', $facility_cd)
                    //         ->update(['comp_stat' => 'FBL', 'updated_by' => $facility_user, 'updated_dt' => date('Y-m-d H:i:s')]);
                }
                
                //  ELSE IF EXISTS
                else{
                    // THEN UPDATE RECORD IN THE `additionaltest` TABLE
                    AdditionalTest::where('donation_id', $donation_id)
                            ->update(['nat' => $nat_result, 
                            'nat_by' => $facility_user, 
                            'nat_verifier' => $verifier,
                            'updated_at' => date('Y-m-d H:i:s')]);
                }
                
                // RETURN RESPONSE
            }
        }
        // EXTRACT DATA

        return response()->json([
            'message' => 'Nat result/s has been saved.',
            'status' => 1
        ], 200);
    } /* saveNatResult() */
    // ///////////////////////////////////////////////// NAT ///////////////////////////////////

    
    // ///////////////////////////////////////////////// ZIKA ///////////////////////////////////
    /*
    SELECT c.donation_id, c.component_cd, rc.component_abbr
    FROM component c
    LEFT JOIN r_cp_component_codes rc ON c.component_cd = rc.component_code
    LEFT JOIN additionaltest ad ON c.donation_id = ad.donation_id
    WHERE c.created_dt BETWEEN '2020-07-01' AND '2020-10-17'
    AND c.location = '13109'
    AND c.comp_stat = 'FBT'
    AND rc.component_abbr IS NOT NULL 
    AND ad.zika IS NULL AND ad.zika_by IS NULL
    */
    public function getComponentsForZikaTest(Request $request){
        $ids            = [];
        $from           = date($request['date_from']);
        $to             = date($request['date_to']);
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];
        
        $sql =" SELECT c.donation_id, c.component_cd, rc.component_abbr
                FROM component c
                LEFT JOIN r_cp_component_codes rc ON c.component_cd = rc.component_code
                LEFT JOIN additionaltest ad ON c.donation_id = ad.donation_id
                WHERE c.created_dt BETWEEN '$from' AND '$to'
                AND c.location = '$facility_cd'
                AND c.comp_stat = 'FBT'
                AND rc.component_abbr IS NOT NULL 
                AND ad.zika IS NULL AND ad.zika_by IS NULL ";

        $zika = DB::select($sql);

        \Log::info($zika);
        
        $zika = json_decode(json_encode($zika), true);
        // return($zika);
        
        if($zika){
            for($i = 0; $i < count($zika); $i++){
                $ids[$i]['donation_id'] = $zika[$i]['donation_id'];
                $ids[$i]['component_abbr'] = $zika[$i]['component_abbr'];
                $ids[$i]['zika_result'] = "";
            }
            
            \Log::info($ids);
            return $ids;
        } else {
            return false;
        }
    } /* getComponentsForZikaTest() */


    public function saveZikaResult(Request $request){
        $facility_user  = Session::get('userInfo')['user_id'];
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

        $verifier       = $request->get('verifier');
        
        $zika_results  = $request->get('zika_results');
        \Log::info($zika_results);
        // return $zika_results;

        // EXTRACT DATA
        foreach($zika_results as $z => $zr){
            
            // FOR NON EMPTY FIELDS
            if($zr['zika_result']){
                
                $donation_id = $zr['donation_id'];
                $zika_result = $zr['zika_result'];
                
                // CHECK IF DONATION ID ALREADY EXIST AT ADDITIONAL TEST TABLE
                $check_donation_id = AdditionalTest::where('donation_id', '=', $donation_id)->first();
                
                // IF DONATION ID DOESNT EXIST THEN
                if($check_donation_id === null){
                    
                    // INSERT IN THE `additionaltest` TABLE
                    $z = new AdditionalTest;
                    $z->donation_id = $donation_id;
                    $z->zika = $zika_result;
                    $z->zika_by = $facility_user;
                    $z->zika_verifier = $verifier;
                    $z->save();

                    // UPDATE `component.comp_stat` FROM `FBT` TO `FBL`, 
                    // * `updated_by` and `updated_dt`
                    // Component::where('donation_id', $donation_id)
                    //         ->where('location', $facility_cd)
                    //         ->update(['comp_stat' => 'FBL', 'updated_by' => $facility_user, 'updated_dt' => date('Y-m-d H:i:s')]);
                }
                
                //  ELSE IF EXISTS
                else{
                    // THEN UPDATE RECORD IN THE `additionaltest` TABLE
                    AdditionalTest::where('donation_id', $donation_id)
                            ->update(['zika' => $zika_result, 
                                'zika_by' => $facility_user, 
                                'zika_verifier' => $verifier,
                                'updated_at' => date('Y-m-d H:i:s')]);
                }
                
                // RETURN RESPONSE
            }
        }
        // EXTRACT DATA

        return response()->json([
            'message' => 'Zika result/s has been saved.',
            'status' => 1
        ], 200);
    } /* saveZikaResult() */
    // ///////////////////////////////////////////////// ZIKA ///////////////////////////////////



    // ///////////////////////////////////////////////// HNA & HNL ///////////////////////////////////
    /*
    SELECT c.donation_id, c.component_cd, rc.component_abbr
    FROM component c
    LEFT JOIN r_cp_component_codes rc ON c.component_cd = rc.component_code
    LEFT JOIN additional_hna_tests ad ON BINARY c.donation_id = BINARY ad.donation_id
    WHERE c.created_dt BETWEEN '2020-07-01' AND '2020-10-17'
    AND c.location = '11001'
    AND c.comp_stat = 'FBT'
    AND rc.component_abbr IS NOT NULL 
    AND ad.hna_hnl_result IS NULL AND ad.result_by IS NULL
    */
    public function getComponentsForHnaTest(Request $request){
        $ids            = [];
        $from           = date($request['date_from']);
        $to             = date($request['date_to']);
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];
        
        // $sql = "    SELECT c.donation_id, c.component_cd, rc.component_abbr, d.donor_sn
        //             FROM component c
        //             LEFT JOIN r_cp_component_codes rc ON c.component_cd = rc.component_code
        //             LEFT JOIN additional_hla_hna_tests ad ON BINARY c.donation_id = BINARY ad.donation_id
        //             LEFT JOIN `donation` d ON d.donation_id = c.source_donation_id
        //             WHERE c.created_dt BETWEEN '2020-11-05' AND '2020-11-07'
        //             AND c.location = '13109'
        //             AND rc.component_abbr IS NOT NULL
        //             AND ad.result IS NULL 
        //             AND ad.result_by IS NULL  ";
        // $hna_hnl = DB::select($sql)
        
        // FETCHING COMPONENT WITH SOURCE DONATION ID ONLY
        $sql = "    SELECT c.donation_id, c.component_cd, rc.component_abbr, d.donor_sn
                    FROM component c
                    LEFT JOIN r_cp_component_codes rc ON c.component_cd = rc.component_code
                    LEFT JOIN additional_hla_hna_tests ad ON BINARY c.donation_id = BINARY ad.donation_id
                    LEFT JOIN `donation` d ON d.donation_id = c.source_donation_id
                    WHERE c.created_dt BETWEEN '$from' AND '$to'
                    AND c.location = '$facility_cd'
                    AND rc.component_abbr IS NOT NULL
                    AND ad.result IS NULL 
                    AND c.source_donation_id IS NOT NULL
                    AND ad.result_by IS NULL  ";
        $hna_hnl = DB::select($sql);
        
        $hna_hnl = json_decode(json_encode($hna_hnl), true);
        // // return($hna_hnl);
        
        if($hna_hnl){
            for($i = 0; $i < count($hna_hnl); $i++){
                $ids[$i]['donation_id'] = $hna_hnl[$i]['donation_id'];
                $ids[$i]['donor_sn'] = $hna_hnl[$i]['donor_sn'];
                // $ids[$i]['component_abbr'] = $hna_hnl[$i]['component_abbr'];
                $ids[$i]['test_1'] = "";
                $ids[$i]['test_2'] = "";
                $ids[$i]['test_3'] = "";
                $ids[$i]['result'] = "";
            }
            
            \Log::info($ids);
            return $ids;
        } else {
            return false;
        }
    } /* getComponentsForHnaTest() */


    public function saveHnaResult(Request $request){
        $facility_user  = Session::get('userInfo')['user_id'];
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

        $verifier       = $request->get('verifier');
        
        $hna_hnl_results  = $request->get('hna_hnl_results');
        \Log::info($hna_hnl_results);
        // return $hna_hnl_results;
        
        $duplicated_id = '';
        
        $tests = [
            'test_1'            => $request['hna_hnl_results.test_1'],
            'test_2'            => $request['hna_hnl_results.test_2'],
            'test_3'            => $request['hna_hnl_results.test_3'],
        ];
        \Log::info($tests);
        
        // EXTRACT DATA
        foreach($hna_hnl_results as $h => $hn){
            
            // FOR NON EMPTY FIELDS
            if($hn['donation_id']){
                
                $donation_id = $hn['donation_id'];
                // $hna_hnl_result = $hn['hna_hnl_result'];
                $donor_sn = $hn['donor_sn'];
                $test_1 = $hn['test_1'];
                $test_2 = $hn['test_2'];
                $test_3 = $hn['test_3'];
                
                // CHECK IF DONATION ID ALREADY EXIST AT ADDITIONAL TEST TABLE
                $check_donation_id = AdditionalHlaHnaTest::where('donation_id', '=', $donation_id)->first();
                
                // IF DONATION ID DOESNT EXIST THEN
                if($check_donation_id === null){

                    
                    // INSERT IN THE `additional_hna_test` TABLE
                    $h = new AdditionalHlaHnaTest;
                    $h->donation_id = $donation_id;
                    $h->donor_sn = $donor_sn;
                    $h->test_1 = $test_1;
                    $h->test_2 = $test_2;
                    $h->test_3 = $test_3;
                    
                    // GET RESULT BASED ON TEST 1 TO 3
                    // checking for 'P' values
                    $fail = 0;
                    foreach($tests as $key => $value){
                        if(strtoupper($value) == 'P'){
                            \Log::info($key);
                            $fail++;
                        }
                    }

                    $h->result = $fail ? 'P' : 'N';

                    // $h->hna_hnl_result = $hna_hnl_result;
                    $h->result_by = $facility_user;
                    $h->result_dt = date("Y-m-d H:i:s");
                    $h->approved_by = $verifier;
                    $h->approval_dt = date("Y-m-d H:i:s");
                    $h->save();
                    
                }
                
                //  ELSE IF EXISTS
                else{
                    // concut/ list down donation ids
                    $duplicated_id = $duplicated_id.' '.$bt['donation_id'];
                }
                
                // RETURN RESPONSE
            }
        }
        // EXTRACT DATA

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
                'message' => 'HNA & HNL result/s has been saved.',
                'status' => 1
            ], 200);
        }
    } /* saveHnaResult() */

    
    // ///////////////////////////////////////////////// HNA & HNL ///////////////////////////////////
}

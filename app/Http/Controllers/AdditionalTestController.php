<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AdditionalTest;
use App\AdditionalHlaHnaTest;
use App\RCpComponentCode;
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
        
        // $sql =" SELECT c.donation_id, c.component_cd, rc.component_abbr, c.source_donation_id, d.collection_method as method
        //         FROM `donation` c
        //         LEFT JOIN r_cp_component_codes rc ON c.component_cd = rc.component_code
        //         LEFT JOIN additionaltest ad ON c.donation_id = ad.donation_id
        //         LEFT JOIN donation d ON c.donation_id = d.donation_id
        //         WHERE c.created_dt BETWEEN '$from' AND '$to'
        //         AND c.location = '$facility_cd'
        //         AND c.comp_stat = 'FBT'
        //         AND c.component_cd >= 100
        //         -- AND c.source_donation_id != c.donation_id
        //         AND rc.component_abbr IS NOT NULL 
        //         AND ad.nat IS NULL AND ad.nat_by IS NULL ";  
        /**/

        $sql = "    SELECT d.donation_id, ad.nat
                    FROM `donation` d
                    LEFT JOIN additionaltest ad ON d.donation_id = ad.donation_id
                    WHERE d.created_dt BETWEEN '$from' AND '$to'
                    AND d.facility_cd = '$facility_cd'
                    AND d.donation_id IS NOT NULL
                    AND d.sched_id = 'Walk-in'
                    AND d.collection_stat = 'COL'
                    AND ad.nat IS NULL AND ad.nat_by IS NULL    ";
        $nat = DB::select($sql);

        // \Log::info($nat);
        $nat = json_decode(json_encode($nat), true);
        
        // \Log::info(self::removeParent($nat));

        // $nat = self::removeParent($nat);
        \Log::info($nat);

        if($nat){
            for($i = 0; $i < count($nat); $i++){
                $ids[$i]['donation_id'] = $nat[$i]['donation_id'];
                // $ids[$i]['component_abbr'] = $nat[$i]['component_abbr'];
                $ids[$i]['nat_result'] = "";
            }
            
            // \Log::info($ids);
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

                }
                
                //  ELSE IF EXISTS
                else{
                    // THEN UPDATE RECORD IN THE `additionaltest` TABLE
                    AdditionalTest::where('donation_id', $donation_id)
                            ->update([
                                'nat' => $nat_result, 
                                'nat_by' => $facility_user, 
                                'nat_verifier' => $verifier,
                                'updated_at' => date('Y-m-d H:i:s') ]);
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
        
        // $sql =" SELECT c.donation_id, c.component_cd, rc.component_abbr, c.source_donation_id, d.collection_method as method
        //         FROM component c
        //         LEFT JOIN r_cp_component_codes rc ON c.component_cd = rc.component_code
        //         LEFT JOIN additionaltest ad ON c.donation_id = ad.donation_id
        //         LEFT JOIN donation d ON c.donation_id = d.donation_id
        //         WHERE c.created_dt BETWEEN '$from' AND '$to'
        //         AND c.location = '$facility_cd'
        //         AND c.comp_stat = 'FBT'
        //         AND c.component_cd >= 100
        //         AND rc.component_abbr IS NOT NULL 
        //         AND ad.zika IS NULL AND ad.zika_by IS NULL ";

        $sql = "    SELECT d.donation_id, ad.nat
                    FROM `donation` d
                    LEFT JOIN additionaltest ad ON d.donation_id = ad.donation_id
                    WHERE d.created_dt BETWEEN '$from' AND '$to'
                    AND d.facility_cd = '$facility_cd'
                    AND d.donation_id IS NOT NULL
                    AND d.sched_id = 'Walk-in'
                    AND d.collection_stat = 'COL'
                    AND ad.zika IS NULL AND ad.zika_by IS NULL    ";

        $zika = DB::select($sql);

        // \Log::info($zika);
        $zika = json_decode(json_encode($zika), true);
        // return($zika);

        // $zika = self::removeParent($zika);
        \Log::info($zika);
        
        if($zika){
            for($i = 0; $i < count($zika); $i++){
                $ids[$i]['donation_id'] = $zika[$i]['donation_id'];
                // $ids[$i]['component_abbr'] = $zika[$i]['component_abbr'];
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



    // private function removeParent($data){

    //     foreach($data as $key => $value){

    //         if(!strpos($value['donation_id'], "-")){
    //             if($value['method'] == 'P'){
    //                 $if_has_aliquote = Component::select('donation_id')->where('source_donation_id', $value['donation_id'])->get();
    //                 if(count($if_has_aliquote)){
    //                     unset($data[$key]);
    //                 }
    //             }
    //         }
            
    //     }

    //     return array_values($data);

    // }


}

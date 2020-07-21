<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Component;
use App\ComponentCode;
use App\BloodTyping;
use Session;
use DB;

class BloodProcessingController extends Controller
{
    public function getDonationId(Request $request){
        
        $ids            = [];
        $from           = date($request['date_from']);
        $to             = date($request['date_to']);
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

        $sched_id       = 'Walk-in';
        $col_stat       = 'COL';

        if($request['col_method'] == 'P'){      // PHERESIS PROCESS

            $sql = "SELECT *
                    -- SELECT t1.donation_id
                    FROM donation t1
                    LEFT JOIN component t2 ON t1.donation_id = t2.donation_id
                    -- WHERE t2.donation_id IS NULL
                    WHERE t1.donation_id IS NOT NULL
                    AND t1.facility_cd = '$facility_cd'
                    AND t1.sched_id = '$sched_id'
                    AND t1.created_dt BETWEEN '$from' AND '$to'
                    AND t1.collection_stat = '$col_stat'        
                    AND t1.collection_method = 'P'
                    AND t2.component_vol IS NULL
                    ORDER BY t1.created_dt";

            $donation = DB::select($sql);

            $donation = json_decode(json_encode($donation), true);
            
            if($donation){

                foreach($donation as $key => $val){
                    // $donation[$key]['count_key'] = $key;
                    $ids[$val['donation_id']]['donation_id'] = $val['donation_id'];
                    $ids[$val['donation_id']]['P01'] = "";
                    $ids[$val['donation_id']]['P02'] = "";
                }

                return $ids;
                
            } else{
                return false;
            }

        } else{                                   // WHOLE BLOOD PROCESS

            $sql = "SELECT t1.donation_id
            FROM donation t1
            LEFT JOIN component t2 ON t1.donation_id = t2.donation_id
            WHERE t2.donation_id IS NULL
            AND t1.donation_id IS NOT NULL
            AND t1.facility_cd = '$facility_cd'
            AND t1.sched_id = '$sched_id'
            AND t1.created_dt BETWEEN '$from' AND '$to'
            AND t1.collection_stat = '$col_stat'        
            AND t1.collection_method = 'P'";

            $donation = DB::select($sql);

            $donation = json_decode(json_encode($donation), true);
            
            if($donation){

                foreach($donation as $key => $val){
                    // $donation[$key]['count_key'] = $key;
                    $ids[$val['donation_id']]['donation_id'] = $val['donation_id'];
                    $ids[$val['donation_id']]['plasma'] = "";
                    $ids[$val['donation_id']]['platelets'] = "";
                    $ids[$val['donation_id']]['redcell'] = "";
                    $ids[$val['donation_id']]['whiteblood'] = "";
                    $ids[$val['donation_id']]['stemcell'] = "";
                }

                return $ids;
                
            } else{
                return false;
            }

        }

    }

    public function save(Request $request){

        $blood_processing   = $request->get('blood_processing');
        $collection_method  = $request->get('col_method');
        $verifier           = $request->get('verifier');

        if($collection_method == 'P'){  // PHERESIS PROCESS
            $status = self::pheresisSave($blood_processing, $verifier);

            if($status){
                return response()->json([
                    'message' => 'Blood Typing has been successfully added.',
                    'status' => 1
                ], 200);
            } else{

            }


        } else{                         // WHOLE BLOOD PROCESS

        }


        // //get all donation_ids, put into array
        // $donation_ids_arr  = self::donationIdsToArray($blood_processing);

        // // relation tables: bloodtest, typing
        // $result = DB::table('donation')
        //             ->select('donation.donation_id', 'donation.created_dt', 'blood_typing.blood_type', 'bloodtest.result')
        //             ->leftJoin('bloodtest', 'donation.donation_id', '=', 'bloodtest.donation_id')
        //             ->leftJoin('blood_typing', 'donation.donation_id', '=', 'blood_typing.donation_id')
        //             ->whereIn('donation.donation_id', $donation_ids_arr)
        //             ->get();

        // $result = json_decode(json_encode($result), true);

        // $save_array = self::processSaveArray($result, $blood_processing, $verifier);


        // $status = Component::create($save_array);

        // return $status;


        // return response()->json([
        //     'message' => 'Blood Typing has been successfully added.',
        //     'status' => 1
        // ], 200);

    }

    private function pheresisSave($data, $verifier){

        




    }


    // private function pheresisSave($data, $verifier){


    //     foreach($data as $val){

    //         $donation_id = $val['donation_id'];

    //         $bloodtype = BloodTyping::select('blood_type')
    //                         ->whereDonationId($val['donation_id'])
    //                         ->first();

    //         if($bloodtype){

    //             $sql = "UPDATE component
    //                     SET `blood_type` = '" . $bloodtype['blood_type'] . "' WHERE `donation_id` = '$donation_id' OR `source_donation_id` = '$donation_id'" ;                   
    
    //             DB::select($sql);

    //         }

    //         $total = $val['P01'] + $val['P02'];

    //         // Component::whereDonationId($val['donation_id']) // update mother ID
    //         //     ->update([
    //         //             'component_vol' => $total,
    //         //             // 'blood_type' => $bloodtype['blood_type'] ?? null
    //         //         ]);

    //         // Component::whereDonationId($val['donation_id'] . '-01') // update aliquoted 01
    //         //     ->update([
    //         //             'component_vol' => $val['P01'],
    //         //             // 'blood_type' => $bloodtype['blood_type'] ?? null
    //         //         ]);
    //         // Component::whereDonationId($val['donation_id'] . '-02') // update aliquoted 02
    //         //     ->update([
    //         //             'component_vol' => $val['P02'],
    //         //             // 'blood_type' => $bloodtype['blood_type'] ?? null
    //         //         ]);

    //         \Log::info("*** \nStart process for " . $val['donation_id'] . ": " . date('H:i:s'));

    //         // // update mother ID
    //         // $sql = "UPDATE component        
    //         // SET `component_vol` = '$total' WHERE `donation_id` = '$donation_id'" ;                   

    //         // DB::select($sql);

    //         // // update aliquoted 01
    //         // $sql = "UPDATE component
    //         // SET `component_vol` = '" . $val['P01'] . "' WHERE `donation_id` = '$donation_id-01'" ;                   

    //         // DB::select($sql);

    //         // // update aliquoted 02
    //         // $sql = "UPDATE component
    //         // SET `component_vol` = '" . $val['P02'] . "' WHERE `donation_id` = '$donation_id-02'" ;                   

    //         // DB::select($sql);

    //         DB::table('component')
    //             ->whereDonationId($val['donation_id']) // update mother ID
    //             ->update([
    //                     'component_vol' => $total,
    //                     // 'blood_type' => $bloodtype['blood_type'] ?? null
    //                 ]);

    //         DB::table('component')
    //             ->whereDonationId($val['donation_id'] . '-01') // update aliquoted 01
    //             ->update([
    //                     'component_vol' => $val['P01'],
    //                     // 'blood_type' => $bloodtype['blood_type'] ?? null
    //                 ]);
    //         DB::table('component')
    //             ->whereDonationId($val['donation_id'] . '-02') // update aliquoted 02
    //             ->update([
    //                     'component_vol' => $val['P02'],
    //                     // 'blood_type' => $bloodtype['blood_type'] ?? null
    //                 ]);

    //         \Log::info("End process for " . $val['donation_id'] . ": " . date('H:i:s') . "\n***");
    //     }
        

    //     return true;

    // }

    private function formatDonationIds($data){

        $arr = [];

        foreach($data as $key => $val){
            $arr[$val['donation_id']] = $val;
        }

        return $arr;
    }

    private function donationIdsToArray($data){

        $arr = [];

        foreach($data as $key => $val){
            $arr[] = $val['donation_id'];
        }

        return $arr;

    }

    private function processSaveArray($data, $blood_processing, $verifier){

        $save_array = [];

        $donation_ids_data = self::formatDonationIds($blood_processing);

        foreach($data as $key => $val){

            $donation_id = $val['donation_id'];

            $getProcessingData = $donation_ids_data[$donation_id];

            if($getProcessingData['plasma']){
                $exp = self::getExpiration("80", $val['created_dt']);
                $save_array[] = self::formatSaveArray($val, "80", $exp, $getProcessingData['plasma']);
            }

            if($getProcessingData['platelets']){
                $exp = self::getExpiration("81", $val['created_dt']);
                $save_array[] = self::formatSaveArray($val, "81", $exp, $getProcessingData['platelets']);
            }
            
            if($getProcessingData['redcell']){
                $exp = self::getExpiration("82", $val['created_dt']);
                $save_array[] = self::formatSaveArray($val, "82", $exp, $getProcessingData['redcell']);
            }

            if($getProcessingData['whiteblood']){
                $exp = self::getExpiration("83", $val['created_dt']);
                $save_array[] = self::formatSaveArray($val, "83", $exp, $getProcessingData['whiteblood']);
            }

            if($getProcessingData['stemcell']){
                $exp = self::getExpiration("84", $val['created_dt']);
                $save_array[] = self::formatSaveArray($val, "84", $exp, $getProcessingData['stemcell']);
            }


        }

        return $save_array;

    }

    private function getExpiration($id, $date){

        $components = ComponentCode::select('component_cd','comp_name','exp_interval','exp_interval_type')
                        ->where('component_cd', $id)
                        ->whereDisableFlg('N')
                        ->first();

        // \Log::info($components);

        switch($components['exp_interval_type']){
            case "M":
                $interval = "MONTH";
            break;
            case "Y":
                $interval = "YEAR";
            break;
            case "D":
            default:
                $interval = "DAY";
        }

        $query = "SELECT DATE_ADD('".$date."',INTERVAL ".$components['exp_interval']." ".$interval.") as `expiration_dt`";

        $result = str_replace('00:00:00','23:59:00',DB::select(DB::raw($query))[0]->expiration_dt);

        return $result;
    }

    private function formatSaveArray($data, $ccd, $exp, $vol){

        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];
        $user_id        = Session::get('userInfo')['user_id'];
        $test           = 'FBT';

        if($data['blood_type'] == 'R'){
            $test = 'REA';
        }

        return array(

            'donation_id'   => $data['donation_id'],
            'component_cd'  => $ccd,
            'blood_type'    => $data['blood_type'] ? $data['blood_type'] : null,
            'location'      => $facility_cd,
            'collection_dt' => date('Y-m-d', strtotime($data['created_dt'])),
            'expiration_dt' => $exp,
            'component_vol' => $vol,
            'comp_stat'     => $test,
            'created_by'    => $user_id,
            'created_dt'    => date('Y-m-d H:i:s'),
        );

    }

}

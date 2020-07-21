<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Component;
use App\ComponentCode;
use App\Helpers\ComputeExpiry;
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

            $sql = "SELECT t1.donation_id, t1.blood_bag
            FROM donation t1
            LEFT JOIN component t2 ON t1.donation_id = t2.donation_id
            WHERE t2.donation_id IS NULL
            AND t1.donation_id IS NOT NULL
            AND t1.facility_cd = '$facility_cd'
            AND t1.sched_id = '$sched_id'
            AND t1.created_dt BETWEEN '$from' AND '$to'
            AND t1.collection_stat = '$col_stat'        
            AND t1.collection_type = 'CPC19'";

            $donation = DB::select($sql);

            $donation = json_decode(json_encode($donation), true);
            
            if($donation){

                foreach($donation as $key => $val){
                    // $donation[$key]['count_key'] = $key;
                    $ids[$val['donation_id']]['donation_id'] = $val['donation_id'];
                    $ids[$val['donation_id']]['blood_bag'] = self::wbDisplayBag($val['blood_bag']);
                    $ids[$val['donation_id']]['plasma'] = "";
                    $ids[$val['donation_id']]['redbloodcell'] = "";
                    $ids[$val['donation_id']]['platelets'] = "";
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
        $status             = ""; 

        if($collection_method == 'P'){  // PHERESIS PROCESS

            $status = self::pheresisSave($blood_processing, $verifier);

        } else{                         // WHOLE BLOOD PROCESS

            $status = self::wbSave($blood_processing, $verifier);
        }

        
        if($status){

            return response()->json([
                'message' => 'Blood Typing has been successfully added.',
                'status' => 1
            ], 200);

        } else{

            return response()->json([
                'message' => 'Error.',
                'status' => 0
            ], 200);   

        }

    }

    private function pheresisSave($data, $verifier){

        // Get all ids and search for entries in component table

        $ids = self::donationIdsToArrayPheresis($data);

        $result = Component::whereIn('component.donation_id', $ids)
                    ->get()->toArray();

        $formatted = self::formatDonationIds($result);

        // Get parent ids and search for blood type in blood_typing table

        $parent = self::parentIdsToArrayPheresis($data);

        $bloodtype = BloodTyping::whereIn('donation_id', $parent)
                    ->get()->toArray();
        
        if($bloodtype){
            $bloodtype = self::formatDonationIds($bloodtype);
        }

        // Format data from request
        $from_request = self::formatDonationIds($data);

        // Format array to be used in saving in create eloquent
        $save_array = self::formatPheresisSaveArray($parent, $formatted, $bloodtype, $from_request);


        // Delete records with ids and then save newly created arrays

        Component::whereIn('donation_id', $ids)->delete();
        $status = Component::create($save_array);
        
        if($status){
            return response()->json([
                'message' => 'Blood Typing has been successfully added.',
                'status' => 1
            ], 200);
        } else{
            return response()->json([
                'message' => 'Error.',
                'status' => 0
            ], 200);
        }

    }

    private function wbSave($data, $verifier){

            //get all donation_ids, put into array
            $donation_ids_arr  = self::donationIdsToArrayWb($data);

            // relation tables: bloodtest, typing
            $result = DB::table('donation')
                        ->select('donation.donation_id', 'donation.created_dt', 'blood_typing.blood_type', 'bloodtest.result')
                        ->leftJoin('bloodtest', 'donation.donation_id', '=', 'bloodtest.donation_id')
                        ->leftJoin('blood_typing', 'donation.donation_id', '=', 'blood_typing.donation_id')
                        ->whereIn('donation.donation_id', $donation_ids_arr)
                        ->get();


            $result = json_decode(json_encode($result), true);

            $save_array = self::processSaveArray($result, $data, $verifier);

            $status = Component::create($save_array);

            return $status;


            // return response()->json([
            //     'message' => 'Blood Typing has been successfully added.',
            //     'status' => 1
            // ], 200);
    }

    // General Functions

    private function formatDonationIds($data){

        $arr = [];

        foreach($data as $key => $val){
            $arr[$val['donation_id']] = $val;
        }

        return $arr;
    }

    // Pheresis Functions

    private function donationIdsToArrayPheresis($data){

        $arr = [];

        foreach($data as $key => $val){
            $arr[] = $val['donation_id'];
            $arr[] = $val['donation_id'] . "-01";
            $arr[] = $val['donation_id'] . "-02";
        }

        return $arr;

    }

    private function parentIdsToArrayPheresis($data){

        $arr = [];

        foreach($data as $key => $val){
            $arr[] = $val['donation_id'];
        }

        return $arr;

    }

    private function formatPheresisSaveArray($parent, $data, $blood_type, $from_request){

        $arr = [];

        foreach($parent as $val){

            $type = "";

            if(isset($blood_type[$val])){
                $type = $blood_type[$val]['blood_type'];
            }

            $total = $from_request[$val]['P01'] + $from_request[$val]['P02'];

            // Add blood type in parent and aliquote if blood type exists

            if($type){
                $data[$val]['blood_type'] =  $type;
                $data[$val . "-01"]['blood_type'] =  $type;
                $data[$val . "-02"]['blood_type'] =  $type;
            }

            // Update component_vol of parent
            $data[$val]['component_vol'] = $total;  // Add total volume from 2 aliquote

            // Update component_vol of 2 aliquotes
            $data[$val . "-01"]['component_vol'] = $from_request[$val]['P01'];
            $data[$val . "-02"]['component_vol'] = $from_request[$val]['P02'];

            // Add to $arr variable
            $arr[] = $data[$val];
            $arr[] = $data[$val . "-01"];
            $arr[] = $data[$val . "-02"];
        
        }

        return $arr;

    }

    // Whole Blood Functions

    private function wbDisplayBag($bag){

        switch($bag){
            case "Q":
                return "Quadruple Bag (Leukocyte Reduced)";
            break;
            case "T":
                return "Triple Bag";
            break;
            case "D":
                return "Double Bag";
            break;
            case "S":
                return "Single Bag";
            break;
            default:
                return 0;
        }


    }

    private function donationIdsToArrayWb($data){

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

            if($getProcessingData['blood_bag'] == "Quadruple Bag (Leukocyte Reduced)"){

                if($getProcessingData['plasma']){
                    $exp = ComputeExpiry::getExpiration("100", $val['created_dt']);
                    $save_array[] = self::formatSaveArray($val, "100", $exp, $getProcessingData['plasma']);
                }

                if($getProcessingData['redbloodcell']){
                    $exp = ComputeExpiry::getExpiration("103", $val['created_dt']);
                    $save_array[] = self::formatSaveArray($val, "103", $exp, $getProcessingData['redbloodcell']);
                }
                
                if($getProcessingData['platelets']){
                    $exp = ComputeExpiry::getExpiration("104", $val['created_dt']);
                    $save_array[] = self::formatSaveArray($val, "104", $exp, $getProcessingData['platelets']);
                }

            } else{

                if($getProcessingData['plasma']){
                    $exp = ComputeExpiry::getExpiration("100", $val['created_dt']);
                    $save_array[] = self::formatSaveArray($val, "100", $exp, $getProcessingData['plasma']);
                }

                if($getProcessingData['redbloodcell']){
                    $exp = ComputeExpiry::getExpiration("101", $val['created_dt']);
                    $save_array[] = self::formatSaveArray($val, "101", $exp, $getProcessingData['redbloodcell']);
                }
                
                if($getProcessingData['platelets']){
                    $exp = ComputeExpiry::getExpiration("102", $val['created_dt']);
                    $save_array[] = self::formatSaveArray($val, "102", $exp, $getProcessingData['platelets']);
                }

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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Component;
use Session;


class AliquoteController extends Controller
{
    public function getAliquote(Request $request){

        $total_aliquote = 0;
        $aliquoted = 0;
        $aliquote_count = 0;
        $display_aliquote = false;

        //get mother vol

        $get_total = Component::select('component_vol')
                        ->where('donation_id', $request['donation_id'])
                        ->where('component_cd', $request['comp'])
                        ->first();

        if($get_total){
            $total_aliquote = intval($get_total['component_vol']);
        }

        $data = Component::select('donation_id', 'component_vol', 'created_by', 'created_dt', 'comp_stat')
                        ->where('source_donation_id', $request['donation_id'])
                        ->where('component_cd', $request['comp'])
                        ->get();

        if(count($data)){
            $aliquote_count = count($data);

            foreach($data as $key => $val){
                $aliquoted = intval($aliquoted) + intval($val->component_vol);
            }
        }

        if(strpos($request['donation_id'], '-') === false){
            $display_aliquote = true;
        }

        $allowed_aliquote = $total_aliquote - $aliquoted;

        return compact('total_aliquote', 'allowed_aliquote', 'data', 'aliquote_count', 'display_aliquote');

    }

    public function saveAliquote(Request $request){

        $aliquote_eval = $request['data']['allowed_aliquote'] - $request['aliquote_val'];

        if($aliquote_eval <= 0){
            return response()->json([
                'message' => 'Cannot Aliquote. Value is greater than or equal to allowed value from mother bag.',
                'status' => 0
            ], 200);            
        }

        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

        $mother_bag_data = Component::where('donation_id', $request['donation_id'])
                            ->where('component_cd', $request['comp'])
                            ->first();

        $aliqoute_num = "-" . sprintf('%02d', $request['data']['aliquote_count'] + 1);

        $mother_bag_data->aliqoute_by           = $facility_cd;
        $mother_bag_data->aliqoute_dt           = date('Y-m-d H:i:s');
        $mother_bag_data->component_vol         = $request['aliquote_val'];
        $mother_bag_data->donation_id           = $request['donation_id'] . $aliqoute_num;
        $mother_bag_data->source_donation_id    = $request['donation_id'];
        $mother_bag_data->created_by            = $facility_cd;
        $mother_bag_data->created_dt            = date('Y-m-d H:i:s');

        $status = Component::create($mother_bag_data->toArray());

        return $status;

        if($status){
            return response()->json([
                'message' => 'Aliquote Successfull.',
                'status' => 1
            ], 200);
        } else{
            return response()->json([
                'message' => 'Error.',
                'status' => 0
            ], 200);
        }

        
        // return $request;
        return $mother_bag_data;

    }
}

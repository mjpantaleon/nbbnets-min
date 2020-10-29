<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BloodRequest;
use Session;
use DB;

// use App\RCpComponentCode;

class BloodRequestController extends Controller
{
    public function bloodRequestList(Request $request){
        $selected_dt    = date($request['selected_dt']);
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

        /*
        SELECT request_id, reference, request_type, status 
        FROM `bau_blood_request` 
        WHERE created_dt BETWEEN '2018-01-01' AND '2018-10-31'
        AND facility_cd = '12002'


        SELECT request_id, reference, request_type, status 
        FROM `bau_blood_request` 
        WHERE created_dt LIKE '2018-08-01'
        AND facility_cd = '125
        */

        $sql =" SELECT request_id, reference, request_type, status 
                FROM `bau_blood_request` 
                WHERE created_dt LIKE '%$selected_dt%'
                AND facility_cd = '12002' ";

        $result = DB::select($sql);
        \Log::info($result);
        
        $result = json_decode(json_encode($result), true);
        \Log::info($result);
        return $result;
    }

    public function getCpComponents(){
        $sql = "    SELECT component_code, component_abbr, comp_name
                    FROM `r_cp_component_codes`
                    WHERE disable_flg = 'N' ";
        $cp_comp = DB::select($sql);
        \Log::info($cp_comp);
        
        $cp_comp = json_decode(json_encode($cp_comp), true);
        \Log::info($cp_comp);
        return $cp_comp;
        
    }


}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Component;
use Session;
use DB;

class AdditionalTestController extends Controller
{

    /*
    SELECT c.donation_id, c.component_cd, rc.component_abbr
    FROM component c
    LEFT JOIN r_cp_component_codes rc ON c.component_cd = rc.component_code
    WHERE location = $facility_cd 
    AND c.created_dt BETWEEN '2020-10-01' AND '2020-10-17'
    AND comp_stat = 'FBT'
    */
    public function getComponentsForNatTest(Request $request){
        $ids            = [];
        $from           = date($request['date_from']);
        $to             = date($request['date_to']);
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

        $sql =" SELECT c.donation_id, c.component_cd, rc.component_abbr
                FROM component c
                LEFT JOIN r_cp_component_codes rc ON c.component_cd = rc.component_code
                WHERE location = '$facility_cd' 
                AND c.created_dt BETWEEN '$from' AND '$to'
                AND comp_stat = 'FBT' ";

        $comp_for_nat_test = DB::select($sql);

        \Log::info($comp_for_nat_test);

        $comp_for_nat_test = json_decode(json_encode($comp_for_nat_test), true);
        return($comp_for_nat_test);
    }
}

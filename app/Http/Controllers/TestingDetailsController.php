<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use DB;

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
    }
}

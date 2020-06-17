<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donation;
use App\BloodTyping;
use App\Component;
use Session;
use DB;
use PDO;

class BloodTypingController extends Controller
{
    public function getDonationId(Request $request){
        
        $ids            = [];
        $from           = date($request['date_from']);
        $to             = date($request['date_to']);
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

        $sched_id       = 'Walk-in';
        $col_stat       = 'COL';

        // $donation = Donation::with('bloodtyping')
        //                     ->select('donation_id')
        //                     ->whereNotNull('donation_id')
        //                     ->whereFacilityCd($facility_cd)
        //                     ->whereSchedId($sched_id)
        //                     ->whereBetween('created_dt', [$from, $to])
        //                     ->where('collection_stat', 'COL')
        //                     ->get();

        $sql = "SELECT t1.donation_id
                FROM donation t1
            LEFT JOIN blood_typing t2 ON t1.donation_id = t2.donation_id
        WHERE t2.donation_id IS NULL
        AND t1.donation_id IS NOT NULL
        AND t1.facility_cd = '$facility_cd'
        AND t1.sched_id = '$sched_id'
        AND t1.created_dt BETWEEN '$from' AND '$to'
        AND t1.collection_stat = '$col_stat'";

        $donation = DB::select($sql);

        $donation = json_decode(json_encode($donation), true);
        
        if($donation){

            foreach($donation as $key => $val){
                // $donation[$key]['count_key'] = $key;
                $ids[$val['donation_id']]['donation_id'] = $val['donation_id'];
                $ids[$val['donation_id']]['abo'] = "";
                $ids[$val['donation_id']]['rh'] = "";
            }

            return $ids;
            
        } else{
            return false;
        }

    }

    public function save(Request $request){

        $blood_typing   = $request->get('blood_typing');
        $verifier       = $request->get('verifier');
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];
        $user_id        = Session::get('userInfo')['user_id'];
 
        foreach($blood_typing as $d){

            $t = new BloodTyping;
            $t->bloodtyping_no = BloodTyping::generateNo($facility_cd);
            $t->facility_cd = $facility_cd;
            $t->bloodtyping_dt = date('Y-m-d H:i:s');
            $t->donation_id = $d['donation_id'];
            $t->blood_type = $d['abo'].' '.$d['rh'];
            $t->reviewed_endorsed_by = $verifier;
            $t->created_by = $user_id;
            $t->created_dt = date('Y-m-d H:i:s');
            $t->save();
            
            Component::whereDonationId($d['donation_id'])
                ->update(['blood_type' => $d['abo'].' '.$d['rh']]);

        }



    }

}

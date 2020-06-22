<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donation;
use Session;

class BloodLabellingController extends Controller
{
    public function getDonationId(Request $request){
        
        $ids            = [];
        $from           = date($request['date_from']);
        $to             = date($request['date_to']);
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

        $sched_id       = 'Walk-in';
        $col_stat       = 'COL';

        // $donation = Donation::with('type','labels','processing','test','additionaltest','units','donor_min')
        //                     ->whereNotNull('donation_id')
        //                     ->whereFacilityCd($facility_cd)
        //                     ->whereSchedId($sched_id)
        //                     ->whereBetween('created_dt', [$from, $to])
        //                     ->where('collection_stat', $col_stat)
        //                     ->get();

        $donation = Donation::with('type','labels','test','additionaltest','units','donor_min')
                            ->whereNotNull('donation_id')
                            ->whereFacilityCd($facility_cd)
                            ->whereSchedId($sched_id)
                            ->whereBetween('created_dt', [$from, $to])
                            ->where('collection_stat', $col_stat)
                            ->get();

        // $donations = Donation::with('type','labels','processing','test','additionaltest','units','donor_min')
                            // ->whereNotNull('donation_id')
                            // ->whereFacilityCd($facility_cd)
                            // ->whereNotNull('blood_bag')
                            // ->whereSchedId($sched_id)
                            // ->whereBetween('created_dt',[$from,$to])
                            // ->get();
       
        // $sql = "SELECT t1.donation_id
        //         FROM donation t1
        //     LEFT JOIN blood_typing t2 ON t1.donation_id = t2.donation_id
        // WHERE t2.donation_id IS NULL
        // AND t1.donation_id IS NOT NULL
        // AND t1.facility_cd = '$facility_cd'
        // AND t1.sched_id = '$sched_id'
        // AND t1.created_dt BETWEEN '$from' AND '$to'
        // AND t1.collection_stat = '$col_stat'";

        // $donation = DB::select($sql);
        // \Log::info($donation);

        // $donation = json_decode(json_encode($donation), true);

        return $donation;
        
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
}

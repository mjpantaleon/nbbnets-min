<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donation;

class BloodTypingController extends Controller
{
    public function getDonationId(Request $request){
        
        $from = date($request['date_from']);
        $to = date($request['date_to']);
        $ids = [];

        $donation = Donation::select('donation_id')
                            ->whereBetween('created_dt', [$from, $to])
                            ->where('collection_stat', 'COL')
                            ->get();

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

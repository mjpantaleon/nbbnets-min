<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donation;
use App\BloodTyping;
use App\Component;
use App\AdditionalTest;
use Session;
use DB;
use PDO;
use Carbon\Carbon;

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
        AND t1.collection_stat = '$col_stat'
        AND (t1.collection_method = 'P'
        OR t1.collection_type = 'CPC19'
        OR t1.collection_type = 'PHE')";

        $donation = DB::select($sql);
        // \Log::info($donation);

        $donation = json_decode(json_encode($donation), true);
        
        if($donation){

            foreach($donation as $key => $val){
                // $donation[$key]['count_key'] = $key;
                $ids[$val['donation_id']]['donation_id'] = $val['donation_id'];
                $ids[$val['donation_id']]['abo'] = "";
                $ids[$val['donation_id']]['rh'] = "";
                $ids[$val['donation_id']]['abs'] = "";
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

        $bloodtyping_arr = [];
 
        foreach($blood_typing as $d){

            // $bloodtyping_arr[] = array(
            //     'facility_cd'           => $facility_cd,
            //     'bloodtyping_dt'        => date('Y-m-d H:i:s'),
            //     'donation_id'           => $d['donation_id'],
            //     'blood_type'            => $d['abo'].' '.$d['rh'],
            //     'reviewed_endorsed_by'  => $verifier,
            //     'created_by'            => $user_id,
            //     'created_dt'            => date('Y-m-d H:i:s'),
            // );

            $bloodtype = new BloodTyping;
            // $bloodtype->bloodtyping_no = BloodTyping::generateNo($facility_cd);
            $bloodtype->facility_cd = $facility_cd;
            $bloodtype->bloodtyping_dt = date('Y-m-d H:i:s');
            $bloodtype->donation_id = $d['donation_id'];
            $bloodtype->blood_type = $d['abo'].' '.$d['rh'];
            $bloodtype->reviewed_endorsed_by = $verifier;
            $bloodtype->created_by = $user_id;
            $bloodtype->created_dt = date('Y-m-d H:i:s');
            $res = $bloodtype->save();


            // ADDED: Save in Antibody Screening Table
            $antibody = AdditionalTest::where('donation_id', $d['donation_id'])->first();

            if($antibody){
                AdditionalTest::where('donation_id', 'LIKE', $d['donation_id'] . '%')
                            ->update([
                                'antibody' => $d['abs'] == 'Positive' ? 'P' : 'N',
                                'antibody_by' => $user_id,
                                'antibody_verifier' => $verifier,
                            ]);
            } else{
                $antibody_data = new AdditionalTest;
                $antibody_data->donation_id         = $d['donation_id'];
                $antibody_data->antibody            = $d['abs'] == 'Positive' ? 'P' : 'N';
                $antibody_data->antibody_by         = $user_id;
                $antibody_data->antibody_verifier   = $verifier;
                $r                                  = $antibody_data->save();
            }

            // \Log::info($res);
            
            $res2 = Component::where('donation_id', 'LIKE', $d['donation_id'] . '%')
                ->update(['blood_type' => $d['abo'].' '.$d['rh']]);
                // ->get();

            // \Log::info($res2);

        }

        // return $status;

        return 'Blood unit/s with Blood type/s has been successfully added.';

        // return response()->json([
        //     'message' => 'Blood Typing has been successfully added.',
        //     'status' => 1
        // ], 200);

    }

}

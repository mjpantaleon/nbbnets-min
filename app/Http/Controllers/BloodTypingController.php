<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donation;
use App\BloodTyping;
use App\Component;
use App\PreScreenedDonor;
use App\Testing;
use App\Donor;
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
       
        // $sql = "SELECT t1.donation_id
        //         FROM donation t1
        //     LEFT JOIN blood_typing t2 ON t1.donation_id = t2.donation_id
        // WHERE t2.donation_id IS NULL
        // AND t1.donation_id IS NOT NULL
        // AND t1.facility_cd = '$facility_cd'
        // AND t1.sched_id = '$sched_id'
        // AND t1.created_dt BETWEEN '$from' AND '$to'
        // AND t1.collection_stat = '$col_stat'";
        // AND (t1.collection_method = 'P'
        // OR t1.collection_type = 'CPC19'
        // OR t1.collection_type = 'PHE')

        $sql = "  SELECT ps.donor_sn, ig.donation_id, bt.bloodtyping_no
                    FROM `pre_screened_donors` ps
                    LEFT JOIN `igg_results` ig ON ig.donor_sn = ps.donor_sn
                    LEFT JOIN `blood_typing` bt ON bt.donation_id = ig.donation_id
                    COLLATE utf8_general_ci
                    WHERE ps.approval_dt BETWEEN '$from' AND '$to'
                    AND ps.status = '3'
                    AND ig.igg != 'N'
                    AND ps.facility_cd LIKE $facility_cd
                    AND bt.bloodtyping_no IS NULL
                    ORDER BY ps.approval_dt ASC ";

        $donation = DB::select($sql);

        $donation = json_decode(json_encode($donation), true);

        if($donation){

            foreach($donation as $key => $val){
                // $donation[$key]['count_key'] = $key;
                $ids[$val['donation_id']]['donation_id'] = $val['donation_id'];
                $ids[$val['donation_id']]['donor_sn'] = $val['donor_sn'];
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

        $sched_id       = 'Walk-in';

        $bloodtyping_arr = [];
 
        foreach($blood_typing as $d){

            $donation_id = $d['donation_id'];
            $donor_sn = $d['donor_sn'];

            \Log::info($d);

            $antibody               = 'N';
            $donation_stat          = 'Y';
            $donation_stat_donor    = 'Y';
            $mh_pe_stat             = 'A';
            $mh_pe_deferral         = null;
            $donor_stat             = 'A';
            $deferral_basis         = null;

            if($d['abs'] == 'Pos'){
                $antibody               = 'P';
                $donation_stat          = 'REA';
                $donation_stat_donor    = 'R';
                $mh_pe_stat             = 'PD';
                $mh_pe_deferral         = 'TTI';
                $donor_stat             = 'PD';
                $deferral_basis         = 'ABS';
            }

            // $bloodtyping_arr[] = array(
            //     'facility_cd'           => $facility_cd,
            //     'bloodtyping_dt'        => date('Y-m-d H:i:s'),
            //     'donation_id'           => $d['donation_id'],
            //     'blood_type'            => $d['abo'].' '.$d['rh'],
            //     'reviewed_endorsed_by'  => $verifier,
            //     'created_by'            => $user_id,
            //     'created_dt'            => date('Y-m-d H:i:s'),
            // );

            $has_blood_typing = BloodTyping::where('donation_id', $donation_id)->get();

            if(!count($has_blood_typing)){
                
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
                $has_antibody = AdditionalTest::where('donation_id', $d['donation_id'])->first();
    
                if($has_antibody){
                    AdditionalTest::where('donation_id', 'LIKE', $d['donation_id'] . '%')
                                ->update([
                                    'antibody' => $antibody,
                                    'antibody_by' => $user_id,
                                    'antibody_verifier' => $verifier,
                                ]);
                } else{
                    $antibody_data = new AdditionalTest;
                    $antibody_data->donation_id         = $d['donation_id'];
                    $antibody_data->antibody            = $antibody;
                    $antibody_data->antibody_by         = $user_id;
                    $antibody_data->antibody_verifier   = $verifier;
                    $r                                  = $antibody_data->save();
                    \Log::info($r);
                }
    
                
                /*
                    Check if there is an existing donation in the donations table,
                    If there is an entry, update
                    If not exists, create
                    Redundant function, may be enhanced by creating a new class
                */
    
                // $has_donation = Donation::select('donation_id')
                //             ->where('donation_id', $donation_id)
                //             ->get();
    
    
                // if(count($has_donation)){
                    
                //     // $update_donation = Donation::where('donation_id', $donation_id)
                //     Donation::where('donation_id', $donation_id)
                //             ->update([
                //                 'donation_stat' => $d['abs'] == 'Pos' ? 'REA' : 'Y',
                //                 'mh_pe_stat' => $d['abs'] == 'Pos' ? 'PD' : 'A',
                //                 'mh_pe_deferral' => $d['abs'] == 'Pos' ? 'ABS' : null,
                //                 'approved_by' => $verifier
                //             ]);

                //     // \Log::info($update_donation);
                    
                // } else{
    
                //     // INSERT record at `donation` table
                //     $seqno = Donation::generateSeqno($facility_cd);
    
                //     $dn = new Donation;
                //     $dn->seqno = $seqno;
                //     $dn->donation_id = $donation_id;
                //     $dn->donor_sn = $donor_sn;
                //     $dn->pre_registered = 'Y';
                //     $dn->sched_id = $sched_id;
                //     $dn->donation_stat = $d['abs'] == 'Pos' ? 'REA' : 'Y';
                //     $dn->mh_pe_stat = $d['abs'] == 'Pos' ? 'PD' : 'A';
                //     $dn->mh_pe_deferral = $d['abs'] == 'Pos' ? 'ABS' : null;
                //     $dn->facility_cd = $facility_cd;
                //     // $dn->created_dt = date('Y-m-d H:i:s');
                //     $dn->approved_by = $verifier;
                //     $dn->save();
                // }

                /**
                 *  Check if donation_stat is Y
                 *  If Y, update the donor
                 *  If N, do not overwrite the donation_stat
                 * 
                 */
                
                //  *Initial value of donation_stat after pre-screening is null -MJ
                $stat = Donor::select('donation_stat')->where('seqno', $donor_sn)->first();

                if($stat['donation_stat'] == 'Y'){

                    // Update 'Donor' table
                    $donor_update_arr = array(
                        'donation_stat' => $d['abs'] == 'Pos' ? 'N' : 'Y',
                        'donor_stat' => $d['abs'] == 'Pos' ? 'PD' : 'A',                
                        'deferral_basis' => $d['abs'] == 'Pos' ? 'ABS' : null                
                    );

                    $stat = Donor::where('seqno', $donor_sn)
                    ->update($donor_update_arr);

                } 
                
                // *added by -MJ
                elseif($stat['donation_stat'] == null){
                    // Update 'Donor' table
                    $donor_update_arr = array(
                        'donation_stat' => $d['abs'] == 'Pos' ? 'N' : 'Y',
                        'donor_stat' => $d['abs'] == 'Pos' ? 'PD' : 'A',                
                        'deferral_basis' => $d['abs'] == 'Pos' ? 'ABS' : null                
                    );

                    $stat = Donor::where('seqno', $donor_sn)
                    ->update($donor_update_arr);
                }

                \Log::info($stat);
    
    
                // $res2 = Component::where('donation_id', 'LIKE', $d['donation_id'] . '%')
                //                 ->update(['blood_type' => $d['abo'].' '.$d['rh']]);
                            // ->get();
    
                /*
                    Check if there are records in blood testing,
                    If there is an entry, change the status in Pre screened donor to 2
                */
    
                $has_tti = Testing::select('donation_id')
                            ->where('donation_id', $donation_id)
                            ->get();
    
                if(count($has_tti)){
                    // UPDATE `pre_screened_donors` table
                    PreScreenedDonor::where('donor_sn', $donor_sn)
                        ->update(['status' => '2']);
                }
                
            } else{

                /** 
                 *  Duplicate Donation ID
                */
                return response()->json([
                    'message' => "This donation IDs already exist: \n $donation_id",
                    'status' => 1
                ], 200);

            }


        }

        // return $status;

        return 'Blood unit/s with Blood type/s has been successfully added.';

        // return response()->json([
        //     'message' => 'Blood Typing has been successfully added.',
        //     'status' => 1
        // ], 200);

    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donation;
// use App\ComponentCode;
use App\RCpComponentCode;
use App\Label;
use Session;

class BloodLabellingController extends Controller
{
    public function getDonationId(Request $request){
        
        $unit_data      = [];
        $from           = date($request['date_from']);
        $to             = date($request['date_to']);
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];

        $sched_id       = 'Walk-in';
        $col_stat       = 'COL';

        $donation = Donation::with('type','labels','test','additionaltest','units','donor_min')
                            ->whereNotNull('donation_id')
                            ->whereNotNull('donor_sn')
                            ->whereFacilityCd($facility_cd)
                            ->whereSchedId($sched_id)
                            ->whereBetween('created_dt', [$from, $to])
                            ->where('collection_stat', $col_stat)
                            ->get();

        // $sql = "    SELECT * FROM 
        //             donation d 
        //             LEFT JOIN donor dd ON d.donor_sn = dd.seqno
        //             LEFT JOIN 
        //             WHERE d.donation_id = 'NVBSP20209900011' ";
        // $donor_details = \DB::select($sql);
        // \Log::info($donor_details);
        
        if($donation){

            $checked = [];

            foreach($donation as $key => $val){

                if($val['units']){

                    foreach($val['units'] as $k => $v){

                        $code = self::setComponentCode($v['component_code']);
                        // $code = self::setComponentCode($v['component_cd']);

                        if($code){
                            $donation[$key]['units'][$code] = $code;
                            $checked_status = self::labelChecked($val['labels'], $v['component_code']);
                            // $checked_status = self::labelChecked($val['labels'], $v['component_cd']);
                            if($checked_status){
                                $donation[$key]['units'][$checked_status] = true;
                            }
                            // $checked[$val['donation_id']][$code] = array('checked' => 0);
                            // $donation[$key]['units'][$code]['hasChecked'] = '$code';
                            // \Log::info($val['labels']);
                        }
                        
                    }

                }

            }
            
            return array('data' => $donation, 'checked' => $checked);
            
        } else{
            return false;
        }

    }


    private function setComponentCode($componentCode){

        if($componentCode >= 80){
            // $code = ComponentCode::select('comp_name')
            $code = RCpComponentCode::select('comp_name')
                                ->whereComponentCd($componentCode)
                                ->first();

            return str_replace(' ', '_', strtolower($code['comp_name']));
        }

        return null;
    }

    public function save(Request $request){

        $label_data     = $request->get('label_data');
        $verifier       = $request->get('verifier');
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];
        $user_id        = Session::get('userInfo')['user_id'];

        foreach($label_data as $key => $val){

            $split = explode("-", $val);

            $label = new Label;
            $label->label_no = Label::generateNo($facility_cd);
            $label->facility_cd = $facility_cd;
            $label->label_dt = date('Y-m-d H:i:s');
            $label->label_by = $user_id;
            $label->donation_id = $split[1];
            $label->component_cd = $split[0];
            $label->reprint_count = 0;
            $label->reason = null;
            $label->save();

        }

        return response()->json([
            'message' => 'Blood Label has been saved.',
            'status' => 1
        ], 200);

    }

    private function labelChecked($data, $cd){

        foreach($data as $key => $val){
            if($val['component_cd'] == $cd)
                return "has" . $cd;
        }

        return null;

    }

}

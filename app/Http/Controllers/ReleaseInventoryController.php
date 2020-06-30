<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donation;
use App\ComponentCode;
use App\Component;
use Session;

class ReleaseInventoryController extends Controller
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
        
        if($donation){

            $checked = [];

            foreach($donation as $key => $val){

                if($val['units']){

                    foreach($val['units'] as $k => $v){

                        $code = self::setComponentCode($v['component_cd']);

                        if($code){
                            $donation[$key]['units'][$code] = $code;
                            $checked_status = self::labelChecked($val['labels'], $v['component_cd']);
                            if($checked_status){
                                $donation[$key]['units'][$checked_status] = true;
                            }

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
            $code = ComponentCode::select('comp_name')
                                ->whereComponentCd($componentCode)
                                ->first();

            return str_replace(' ', '_', strtolower($code['comp_name']));
        }

        return null;
    }

    public function update(Request $request){

        $comp_data     = $request->get('label_data');
        $verifier       = $request->get('verifier');
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];
        $user_id        = Session::get('userInfo')['user_id'];

        foreach($comp_data as $key => $val){

            $split = explode("-", $val);

            $comp = Component::whereDonationId($split[1])
                            ->whereComponentCd($split[0])
                            ->first();
            $comp->comp_stat = 'AVA';
            $comp->save();
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donation;
use App\ComponentCode;
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
        
        if($donation){

            $checked = [];

            foreach($donation as $key => $val){

                if($val['units']){

                    foreach($val['units'] as $k => $v){

                        $code = self::setComponentCode($v['component_cd']);

                        if($code){
                            $donation[$key]['units'][$code] = $code;
                            $checked[$val['donation_id']][$code] = array('checked' => 0);
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


}

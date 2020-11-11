<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donation;
use App\ComponentCode;
use App\RCpComponentCode;
use App\Component;
use App\PheresisBloodLabel;
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

        if($request['col_method'] == 'P'){      // PHERESIS PROCESS

            $donation = Donation::with('type','pheresis_label','test','additionaltest','units','donor_min','aliquote_component')
                                ->whereNotNull('donation_id')
                                ->whereNotNull('donor_sn')
                                ->whereFacilityCd($facility_cd)
                                ->whereSchedId($sched_id)
                                ->whereBetween('created_dt', [$from, $to])
                                ->where('collection_stat', $col_stat)
                                ->whereCollectionMethod('P')
                                ->get();

            if($donation){

                $checked = [];
    
                foreach($donation as $key => $val){

                    $donation[$key]['units']["showP01"] = false;
                    $donation[$key]['units']["showP02"] = false;
                    $donation[$key]['units']["showP03"] = false;

                    $aliqoutes = Component::select('donation_id')->where('source_donation_id', $val['donation_id'])->get();

                    // foreach ($aliqoutes as $k => $v) {
                    //     $aliquote_number = explode("-", $v['donation_id']);
                    //     $donation[$key]['units']["showP" . $aliquote_number[1]] = true;

                    // }

                    if(count($aliqoutes)){

                        $aliquote_arr = self::getCompStat($val['aliquote_component']);

                        if($val['pheresis_label']){
        
                            foreach($val['pheresis_label'] as $k => $v){
    
                                if( strpos($v->donation_id, "-01") !== false ){
                                    $donation[$key]['units']["p01"] = $aliquote_arr[$v->donation_id];
                                } elseif( strpos($v->donation_id, "-02") !== false ){
                                    $donation[$key]['units']["p02"] = $aliquote_arr[$v->donation_id];
                                } elseif( strpos($v->donation_id, "-03") !== false ){
                                    $donation[$key]['units']["p03"] = $aliquote_arr[$v->donation_id];
                                }
        
                            }
        
                        }

                    } else{

                        $has_pheresis_save = PheresisBloodLabel::where('donation_id', $val['donation_id'])
                                            ->get();

                        \Log::info($val['donation_id']);
                        \Log::info($has_pheresis_save);

                        if(count($has_pheresis_save)){
                            $donation[$key]['units']["showM01"] = true;

                            $aliqoutes = Component::select('comp_stat')->where('donation_id', $val['donation_id'])->first();
                            $donation[$key]['units']["m01"] = $aliqoutes['comp_stat'];
                        } else{
                            $donation[$key]['units']["showM01"] = false;
                        }
                    }

                }

                // \Log::info($donation);
                
                return array('data' => $donation, 'checked' => $checked);
                
            } else{
                return false;
            }

        } else{                                 // WHOLE BLOOD PROCESS

            $donation = Donation::with('type','labels','test','additionaltest','units','donor_min')
                                ->whereNotNull('donation_id')
                                ->whereNotNull('donor_sn')
                                ->whereFacilityCd($facility_cd)
                                ->whereSchedId($sched_id)
                                ->whereBetween('created_dt', [$from, $to])
                                ->where('collection_stat', $col_stat)
                                ->whereCollectionType('CPC19')
                                ->get();

            if($donation){

                $checked = [];

                foreach($donation as $key => $val){

                    if($val['units']){

                        foreach($val['units'] as $k => $v){

                            $code = self::setComponentCode($v['component_cd']);

                            if($code){

                                $donation[$key]['units'][$code] = $v['comp_stat'];

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

    }

    private function setComponentCode($componentCode){

        if($componentCode >= 100){
            $code = RCpComponentCode::select('component_abbr')
                                ->whereComponentCode($componentCode)
                                ->first();

            return $code['component_abbr'];
        }

        return null;
    }

    public function update(Request $request){

        $comp_data      = $request->get('release_data');
        $verifier       = $request->get('verifier');
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];
        $user_id        = Session::get('userInfo')['user_id'];
        $method         = $request->get('method');

        if($method == 'P'){

            $all_id = self::mergeMotherDonationId($comp_data);

            $comp = Component::whereIn('donation_id', $all_id)
                        ->update(['comp_stat' => 'AVA']);

            return response()->json([
                'message' => 'Blood Label has been saved.',
                'status' => 1
            ], 200);

        } else{

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

    }

    private function labelChecked($data, $cd){

        foreach($data as $key => $val){
            if($val['component_cd'] == $cd)
                return "has" . $cd;
        }

        return null;

    }

    private function getCompStat($data){

        $arr = [];

        foreach($data as $val){
            $arr[$val['donation_id']] = $val['comp_stat'];
        }

        return $arr;
    }

    private function mergeMotherDonationId($ids){

        $arr = [];

        foreach($ids as $val){

            $split_id = explode('-', $val);

            if(!in_array($split_id[0], $arr)){
                $arr[] = $split_id[0];
            }

        }

        return array_merge($arr, $ids);

    }

}

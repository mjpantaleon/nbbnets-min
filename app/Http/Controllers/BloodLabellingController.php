<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Donation;
use App\Component;
// use App\ComponentCode;
use App\RCpComponentCode;
use App\Label;
use App\PheresisBloodLabel;
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

        $donation       = ""; 

        if($request['col_method'] == 'P'){      // PHERESIS PROCESS

            $donation = Donation::with('type','labels','test','additionaltest','units','donor_min', 'pheresis_label')
                                ->whereNotNull('donation_id')
                                ->whereNotNull('donor_sn')
                                ->whereFacilityCd($facility_cd)
                                ->whereSchedId($sched_id)
                                ->whereBetween('created_dt', [$from, $to])
                                ->where('collection_stat', $col_stat)
                                ->where('collection_method', "P")
                                ->get();

            // \Log::info($donation);

            if($donation){

                $checked = [];
    
                foreach($donation as $key => $val){

                    $aliqoutes = Component::select('donation_id', 'source_donation_id')->where('source_donation_id', $val['donation_id'])->get();

                    if(count($aliqoutes)){

                        foreach ($aliqoutes as $k => $v) {
                            $aliquote_number = explode("-", $v['donation_id']);
                            $donation[$key]['units']["showP" . $aliquote_number[1]] = true;
    
                        }
        
                        if($val['pheresis_label']){
        
                            foreach($val['pheresis_label'] as $k => $v){
    
                                if( strpos($v->donation_id, "-01") !== false ){
                                    $donation[$key]['units']["hasp01"] = true;
                                } elseif( strpos($v->donation_id, "-02") !== false ){
                                    $donation[$key]['units']["hasp02"] = true;
                                }  elseif( strpos($v->donation_id, "-03") !== false ){
                                    $donation[$key]['units']["hasp03"] = true;
                                } 
    
                            }
        
                        }

                    } else{

                        $donation[$key]['units']["showM01"] = true;

                        if(count($val['pheresis_label'])){
                            $donation[$key]['units']["hasm01"] = true;
                        } else{
                            $donation[$key]['units']["hasm01"] = false;
                        }
                        
                    }

                }
                
                return array('data' => $donation, 'checked' => $checked);
                
            } else{
                return false;
            }


        } 
        
        // ADDED: Modified query to cater whole blood pheresis

        elseif($request['col_method'] == 'WB'){                                 // WHOLE BLOOD PROCESS

            $donation = Donation::with('type','labels','test','additionaltest','units','donor_min')
                                ->whereNotNull('donation_id')
                                ->whereNotNull('donor_sn')
                                ->whereFacilityCd($facility_cd)
                                ->whereSchedId($sched_id)
                                ->whereBetween('created_dt', [$from, $to])
                                ->where('collection_stat', $col_stat)
                                ->where('collection_type', "CPC19")
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

        else{                                 // WHOLE BLOOD PHERESIS PROCESS

            $donation = Donation::with('type','labels','test','additionaltest','units','donor_min')
                                ->whereNotNull('donation_id')
                                ->whereNotNull('donor_sn')
                                ->whereFacilityCd($facility_cd)
                                ->whereSchedId($sched_id)
                                ->whereBetween('created_dt', [$from, $to])
                                ->where('collection_stat', $col_stat)
                                ->where('collection_type', "PHE")
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

        // $sql = "    SELECT * FROM 
        //             donation d 
        //             LEFT JOIN donor dd ON d.donor_sn = dd.seqno
        //             LEFT JOIN 
        //             WHERE d.donation_id = 'NVBSP20209900011' ";
        // $donor_details = \DB::select($sql);
        // \Log::info($donor_details);
        


    }


    private function setComponentCode($componentCode){  // Used only if collection method is 'WB'

        if((int)$componentCode >= 100){
            // $code = ComponentCode::select('comp_name')
            $code = RCpComponentCode::select('component_abbr')
                                ->whereComponentCode($componentCode)
                                ->first();

            return $code['component_abbr'];
        }

        return null;
    }

    public function save(Request $request){

        $label_data     = $request->get('label_data');
        $verifier       = $request->get('verifier');
        $method         = $request->get('method');
        $facility_cd    = Session::get('userInfo')['facility']['facility_cd'];
        $user_id        = Session::get('userInfo')['user_id'];

        if($method == 'P'){
            
            if(count($label_data) === 1){

                $label = new PheresisBloodLabel;
                $label->label_no = PheresisBloodLabel::generateNo($facility_cd);
                $label->facility_cd = $facility_cd;
                $label->label_dt = date('Y-m-d H:i:s');
                $label->label_by = $user_id;
                $label->donation_id = $label_data[0];
                $label->source_donation_id = $label_data[0];
                $label->component_cd = 100;
                $label->reprint_count = 0;
                $label->reason = null;

                if(strpos($label_data[0], '-')){ // if only 1 aliquote will be saved, to prevent error in source_donation_id field

                    $split = explode("-", $label_data[0]);
                    $label->source_donation_id = $split[0];
                }

                $label->save();

            } else{

                foreach($label_data as $key => $val){

                    $split = explode("-", $val);
        
                    $label = new PheresisBloodLabel;
                    $label->label_no = PheresisBloodLabel::generateNo($facility_cd);
                    $label->facility_cd = $facility_cd;
                    $label->label_dt = date('Y-m-d H:i:s');
                    $label->label_by = $user_id;
                    $label->donation_id = $val;
                    $label->source_donation_id = $split[0];
                    $label->component_cd = 100;
                    $label->reprint_count = 0;
                    $label->reason = null;
                    $label->save();
        
                }

            }

            return response()->json([
                'message' => 'Blood Label has been saved.',
                'status' => 1
            ], 200);

        } else{

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

    }

    private function labelChecked($data, $cd){

        foreach($data as $key => $val){
            if($val['component_cd'] == $cd)
                return "has" . $cd;
        }

        return null;

    }

}

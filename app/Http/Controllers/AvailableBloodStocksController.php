<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Component;
use App\RCpComponentCode;
use Session;

class AvailableBloodStocksController extends Controller
{
    public function getList(){

        $facility_cd = Session::get('userInfo')['facility']['facility_cd'];

        $available = Component::select('blood_type','donation_id','component_vol','expiration_dt','component_cd', 'source_donation_id')
                                ->with('donation_min')
                                ->whereLocation($facility_cd)
                                ->whereIn('comp_stat', array('AVA', 'FLU', 'RES')) // Add status after comp_stat = AVA # removed 'Released' from filter
                                ->where('component_cd', '>=', 100)
                                ->where('expiration_dt','>=', date('Y-m-d') )
                                ->orderBy('created_dt','desc')
                                ->orderBy('donation_id','asc')
                                ->get()
                                ->toArray();

        $available = self::formatData($available);
        // return $available;
        return self::removeParent($available);

    }

    public function updateList(Request $request){

        $id = $request->get('donation_id');
        $facility_cd = Session::get('userInfo')['facility']['facility_cd'];

        if($id){

            $available = Component::select('blood_type','donation_id','component_vol','expiration_dt','component_cd', 'source_donation_id')
                                ->with('donation_min', 'aliqoute_donation')
                                ->where('donation_id', 'LIKE', $id."%")
                                ->whereLocation($facility_cd)
                                ->whereCompStat('AVA')
                                ->where('component_cd', '>=', 100)
                                ->where('expiration_dt','>=', date('Y-m-d') )
                                ->orderBy('expiration_dt','asc')
                                ->orderBy('donation_id','asc')
                                ->get()
                                ->toArray();

            return self::formatData($available);

        } else{
            return self::getList();
        }

    }

    private function getAllComponentAbbreviation(){

        $arr = [];

        $abbr = RCpComponentCode::select('component_code', 'component_abbr')->get();

        foreach($abbr as $val){
            $arr[$val['component_code']] = $val['component_abbr'];
        }

        return $arr;

    }

    private function formatData($data){

        $abbr = self::getAllComponentAbbreviation();

        $arr = [];
        $ret = [];

        // foreach($data as $key => $val){
        //     $arr[$val['donation_id']] = $val;
        // }

        // foreach($arr as $key => $val){

        //     if($val['source_donation_id']){
        //         $created_date = date('F j, Y', strtotime($arr[$val['source_donation_id']]['donation_min']['created_dt']));
        //         $method       = "P";
        //     } else{
        //         $created_date = date('F j, Y', strtotime($val['donation_min']['created_dt']));

        //         if($val['donation_min']['collection_method'] == 'P'){
        //             $method = "P";
        //         } elseif($val['donation_min']['collection_type'] == 'CPC19'){
        //             $method = "WB";
        //         }
        //     }

        //     $diff = strtotime(date('Y-m-d H:i:s')) - strtotime($created_date);

        //     $ret[] = array(

        //         'blood_type'        => $val['blood_type'],
        //         'component_cd'      => $val['component_cd'],
        //         'component_abbr'    => $abbr[$val['component_cd']],
        //         'donation_id'       => $val['donation_id'],
        //         'component_vol'     => $val['component_vol'],
        //         'created_date'      => $created_date,
        //         'expiration_dt'     => date('F j, Y', strtotime($val['expiration_dt'])),
        //         'days_old'          => abs(round($diff / 86400)),
        //         'method'            => $method,

        //     );

        // }

        foreach($data as $key => $val){
            $arr[$val['donation_id']] = $val;
        }

        foreach($data as $key => $val){

            if($val['source_donation_id']){

                foreach($data as $k => $v){
                    if($val['source_donation_id'] == $v['donation_id']){
                        if($v['donation_min']['collection_method'] == 'P'){
                            $created_date = date('F j, Y', strtotime($arr[$val['source_donation_id']]['donation_min']['created_dt']));
                            $method       = "P";
                        } else{
                            $created_date = date('F j, Y', strtotime($v['donation_min']['created_dt']));
                            $method = "WB";
                        }

                        break;
                    }
                }
                
            } else{
                $created_date = date('F j, Y', strtotime($val['donation_min']['created_dt']));

                if($val['donation_min']['collection_method'] == 'P'){
                    $method = "P";
                } elseif($val['donation_min']['collection_type'] == 'CPC19'){
                    $method = "WB";
                }
            }

            $diff = strtotime(date('Y-m-d H:i:s')) - strtotime($created_date);

            if($method == 'P' && $abbr[$val['component_cd']] == 'CP'){
                $comp_abbr = $abbr[$val['component_cd']] . " (Pheresis)";
            } elseif($method == 'WB' && $abbr[$val['component_cd']] == 'CP'){
                $comp_abbr = $abbr[$val['component_cd']] . " (WB)";
            } else{
                $comp_abbr = $abbr[$val['component_cd']];
            }

            $ret[] = array(

                'blood_type'        => $val['blood_type'],
                'component_cd'      => $val['component_cd'],
                'component_abbr'    => $comp_abbr,
                'donation_id'       => $val['donation_id'],
                'component_vol'     => $val['component_vol'],
                'created_date'      => $created_date,
                'expiration_dt'     => date('F j, Y', strtotime($val['expiration_dt'])),
                'days_old'          => abs(round($diff / 86400)),
                'method'            => $method,

            );

        }

        return $ret;

    }

    private function removeParent($data){

        foreach($data as $key => $value){

            if(!strpos($value['donation_id'], "-")){
                if($value['method'] == 'P'){
                    $if_has_aliquote = Component::select('donation_id')->where('source_donation_id', $value['donation_id'])->get();
                    if(count($if_has_aliquote)){
                        unset($data[$key]);
                    }
                }
            }
            
        }

        return array_values($data);

    }

}

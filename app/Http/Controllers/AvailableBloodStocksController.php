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
                                ->whereCompStat('AVA')
                                ->where('component_cd', '>=', 100)
                                ->where('expiration_dt','>=', date('Y-m-d') )
                                ->orderBy('expiration_dt','asc')
                                ->orderBy('donation_id','asc')
                                ->get()
                                ->toArray();

        return self::formatData($available);

    }

    public function updateList(Request $request){

        $id = $request->get('donation_id');
        $facility_cd = Session::get('userInfo')['facility']['facility_cd'];

        if($id){

            $available = Component::select('blood_type','donation_id','component_vol','expiration_dt','component_cd', 'source_donation_id')
                                ->with('donation_min')
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

        foreach($data as $key => $val){
            $arr[$val['donation_id']] = $val;
        }

        foreach($arr as $key => $val){

            \Log::info($val['donation_min']);

            if($val['source_donation_id']){
                $created_date = date('F j, Y', strtotime($arr[$val['source_donation_id']]['donation_min']['created_dt']));
                $method       = "P";
            } else{
                $created_date = date('F j, Y', strtotime($val['donation_min']['created_dt']));

                if($val['donation_min']['collection_method'] == 'P'){
                    $method = "P";
                } elseif($val['donation_min']['collection_type'] == 'CPC19'){
                    $method = "WB";
                }
            }

            $diff = strtotime(date('Y-m-d H:i:s')) - strtotime($created_date);

            $ret[] = array(

                'blood_type'        => $val['blood_type'],
                'component_cd'      => $val['component_cd'],
                'component_abbr'    => $abbr[$val['component_cd']],
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

}

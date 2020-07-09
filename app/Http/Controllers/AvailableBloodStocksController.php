<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Component;
use Session;

class AvailableBloodStocksController extends Controller
{
    public function getList(){

        $facility_cd = Session::get('userInfo')['facility']['facility_cd'];

        $available = Component::select('blood_type','donation_id','component_vol','expiration_dt','component_cd')
                // ->with('donation_min','donation_min.mbd_min','component_min')
                ->with('donation_min','component_code_min')
                ->whereLocation($facility_cd)
                ->whereCompStat('AVA')
                ->where('component_cd', '>=', 80)
                ->where('expiration_dt','>=',date('Y-m-d'))
                ->orderBy('expiration_dt','asc')
                ->get();

        foreach($available as $key => $val){

            $available[$key]['expiration_dt'] = date('F j, Y', strtotime($val['expiration_dt']));
            $available[$key]['donation_min']['created_dt'] = date('F j, Y', strtotime($val['donation_min']['created_dt']));
            $available[$key]['component_abbr'] = self::getComponentAbbreviation($val['component_cd']);

            $diff = strtotime(date('Y-m-d H:i:s')) - strtotime($val['donation_min']['created_dt']);
            $available[$key]['days_old'] = abs(round($diff / 86400));

        }

        return $available;

    }

    public function updateList(Request $request){

        $id = $request->get('donation_id');
        $facility_cd = Session::get('userInfo')['facility']['facility_cd'];

        if($id){
            $available = Component::select('blood_type','donation_id','component_vol','expiration_dt','component_cd')
                                ->with('donation_min','component_code_min')
                                ->whereDonationId($id)
                                ->whereLocation($facility_cd)
                                ->whereCompStat('AVA')
                                ->where('component_cd', '>=', 80)
                                ->where('expiration_dt','>=',date('Y-m-d'))
                                ->orderBy('expiration_dt','asc')
                                ->get();


            foreach($available as $key => $val){

                $available[$key]['expiration_dt'] = date('F j, Y', strtotime($val['expiration_dt']));
                $available[$key]['donation_min']['created_dt'] = date('F j, Y', strtotime($val['donation_min']['created_dt']));
                $available[$key]['component_abbr'] = self::getComponentAbbreviation($val['component_cd']);

                $diff = strtotime(date('Y-m-d H:i:s')) - strtotime($val['donation_min']['created_dt']);
                $available[$key]['days_old'] = abs(round($diff / 86400));

            }

            return $available;

        } else{
            return self::getList();
        }

    }

    private function getComponentAbbreviation($code){

        switch ($code) {
            case "80":
                return "PM";
                break;
            case "81":
                return "PLT";
                break;
            case "82":
                return "RC";
                break;
            case "83":
                return "WBC";
                break;
            case "84":
                return "SC";
                break;
            default:
                return null;
          }

    }

}

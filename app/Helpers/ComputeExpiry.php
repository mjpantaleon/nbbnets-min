<?php

namespace App\Helpers;

use App\RCpComponentCode;
use DB;

class ComputeExpiry
{
    public static function getExpiration($id, $date){

        $components = RCpComponentCode::select('component_code','comp_name','exp_interval','exp_interval_type')
                        ->where('component_code', $id)
                        ->whereDisableFlg('N')
                        ->first();

        // \Log::info($components);

        switch($components['exp_interval_type']){
            case "M":
                $interval = "MONTH";
            break;
            case "Y":
                $interval = "YEAR";
            break;
            case "D":
            default:
                $interval = "DAY";
        }

        $query = "SELECT DATE_ADD('".$date."',INTERVAL ".$components['exp_interval']." ".$interval.") as `expiration_dt`";

        $result = str_replace('00:00:00','23:59:00',DB::select(DB::raw($query))[0]->expiration_dt);

        return $result;
    }

}
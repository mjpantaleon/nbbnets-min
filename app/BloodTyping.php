<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BloodTyping extends Model
{
    protected $table = 'blood_typing';
    protected $primaryKey = 'bloodtyping_no';
    protected $keyType = 'string';

    public $incrementing = false;
    public $timestamps = false;

    static function generateNo($facility_cd,$i = 1,$max = null){
        
        if(!$max){
            $max = BloodTyping::selectRaw('bloodtyping_no')
                    ->whereFacilityCd($facility_cd)
                    ->where('bloodtyping_no','like',$facility_cd.'%')
                    ->orderBy('bloodtyping_no','desc')
                    ->first();
            if($max){
                $max = $max->bloodtyping_no;
            }else if(!$max){
                return $facility_cd.date('Y').str_pad('1',7,'0',STR_PAD_LEFT);
            }
        }

        $num = substr($max,9,strlen($max));
        $num = abs($num);
        $num = $num+$i;
        $new = $facility_cd.date('Y').str_pad($num,7,'0',STR_PAD_LEFT);
        $isExists = BloodTyping::whereBloodtypingNo($new)->first();

        if($isExists){
            $i++;
            return self::generateNo($facility_cd,$i,$max);
        }

        return $new;
    }


}

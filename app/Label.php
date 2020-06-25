<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $table = 'blood_label';
    public $timestamps = false;
    public $incrementing = false;
    protected $primaryKey = 'label_no';

    function unit(){
        return $this->hasOne('App\Blood','donation_id','donation_id')->whereComponentCd($component_cd);
    }

    function donation(){
        return $this->belongsTo('App\Donation','donation_id','donation_id');
    }

    static function generateNo($facility_cd,$i = 1,$max = null){
        if(!$max){
            $max = Label::selectRaw('label_no')
                    ->whereFacilityCd($facility_cd)
                    ->where('label_no','like',$facility_cd.'%')
                    ->orderBy('label_no','desc')
                    ->first();
            if(!$max){
                return $facility_cd.date('Y').str_pad('1',7,'0',STR_PAD_LEFT);
            }else{
                $max = $max->label_no;
            }
        }

        $num = substr($max,9,strlen($max));
        $num = abs($num);
        $num = $num+$i;
        $new = $facility_cd.date('Y').str_pad($num,7,'0',STR_PAD_LEFT);
        $isExists = Label::whereLabelNo($new)->first();
        if($isExists){
            $i++;
            return self::generateNo($facility_cd,$i,$max);
        }

        return $new;
    }
}

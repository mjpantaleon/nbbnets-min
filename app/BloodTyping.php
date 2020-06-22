<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Session;

class BloodTyping extends Model
{
    protected $table = 'blood_typing';
    protected $primaryKey = 'bloodtyping_no';
    // protected $keyType = 'string';

    public $incrementing = false;
    public $timestamps = false;
    protected $guarded = [];


    static function boot(){
      parent::boot();
      self::creating(function ($model) {

        $facility_cd = Session::get('userInfo')['facility']['facility_cd'];

        $max = BloodTyping::selectRaw('bloodtyping_no')
                ->whereFacilityCd($facility_cd)
                ->where('bloodtyping_no','like',$facility_cd.'%')
                ->orderBy('bloodtyping_no','desc')
                ->first();

        if($max){
            $max = $max->bloodtyping_no;
        }else{
            $model->bloodtyping_no = $facility_cd.date('Y').str_pad('1',7,'0',STR_PAD_LEFT);
        }

        $num = substr($max,9,strlen($max));
        $num = abs($num);
        $num = $num + 1;
        $new = $facility_cd.date('Y').str_pad($num,7,'0',STR_PAD_LEFT);

        $model->bloodtyping_no = $new;

      });
    }

}

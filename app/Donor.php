<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    // public function donations(){
    //     return $this->hasMany('App\Donation');
    // }
}

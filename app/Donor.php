<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Donor extends Model
{
    protected $table = 'donor';
    // protected $fillable = ['fname, mname, lname, bdate']; // white list
    public $timestamps = false;

    public function donations(){
        return $this->hasMany('App\Donation');
    }
}

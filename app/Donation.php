<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Donation extends Model
{
    protected $table = 'donation';
    
    protected $guarded = [];
    public $timestamps = false;

    public function donor(){
        return $this->belongsTo('App\Donor', 'seqno');
    }

    // function bloodtyping(){
    //     return $this->hasOne('App\BloodTyping','donation_id','donation_id');
    // }
}

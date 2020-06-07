<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PreScreenedDonor extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function donor(){
        return $this->belongsTo('App\Donor', 'seqno');
    }
}

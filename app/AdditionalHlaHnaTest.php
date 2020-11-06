<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdditionalHlaHnaTest extends Model
{
    protected $table = 'additional_hla_hna_tests';

    protected $guarded = [];
    
    public $timestamps = false;

    function pre_screened_donor(){
        return $this->belongsTo('App\PreScreenedDonor', 'donor_sn', 'donor_sn')->select('donor_sn', 'donation_id');
    }
}

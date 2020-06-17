<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestingDetails extends Model
{
    protected $table = 'bloodtest_dtls';
    public $incrementing = false;
    public $timestamps = false;

    function header(){
        return $this->belongsTo('App\Testing','bloodtest_no','bloodtest_no');
    }
}

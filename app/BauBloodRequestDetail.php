<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BauBloodRequestDetail extends Model
{
    protected $table = 'bau_blood_request_dtls';
    protected $primaryKey = 'request_component_id';

    protected $guarded = [];
    public $timestamps = false;
}

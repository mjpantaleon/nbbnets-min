<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BauPatient extends Model
{
    protected $table = 'bau_patient';
    protected $primaryKey = 'patient_id';

    protected $guarded = [];
    public $timestamps = false;
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BloodTyping extends Model
{
    protected $table = 'blood_typing';
    protected $primaryKey = 'bloodtyping_no';
    public $incrementing = false;
    protected $keyType = 'string';

}

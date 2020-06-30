<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Discard extends Model
{
    protected $table = 'blood_discard';
    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey = 'donation_id';

    
}

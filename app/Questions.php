<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model
{
    public function questionHeader(){
        return $this->belongsTo('App\QuestionsHeader', 'id');
    }
}

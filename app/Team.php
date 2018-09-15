<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    //


    public function project(){
        return $this->belongsTo('App\Project');
    }


    public function project_attendings(){

        //lalala
        return $this->hasMany('App\Project_Attending');
    }

}

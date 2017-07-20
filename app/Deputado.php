<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deputado extends Model
{

    protected $table = 'deputados';

    public function ultimoStatus()
    {
        return $this->hasOne('App\Status');
    }

}

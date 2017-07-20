<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fornecedor extends Model
{

    protected $table = 'fornecedores';

    public function despesas()
    {
        return $this->belongsToMany('App\Despesa');
    }

}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{

    protected $table = 'despesas';

    public function deputado()
    {
        return $this->belongsTo('App\Deputado');
    }

    public function tipoDespesa()
    {
        return $this->belongsTo('App\TipoDespesa', 'tipos_despesas_id');
    }

    public function fornecedores()
    {
        return $this->belongsToMany('App\Fornecedor');
    }

}

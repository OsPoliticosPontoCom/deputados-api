<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\TipoDespesa;

class TipoDespesaController extends Controller
{

    public function __construct()
    {
        //
    }

    public function list() {
        $tiposDespesa = TipoDespesa::all();
        return response()->json($tiposDespesa);
    }

}

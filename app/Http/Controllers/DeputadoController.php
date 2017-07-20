<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Deputado;

class DeputadoController extends Controller
{

    public function __construct()
    {
        //
    }

    public function list() {
        $deputados = Deputado::with('ultimoStatus.gabinete')->get();
        return response()->json($deputados);
    }

}

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

    public function get($id) {
        $deputado = Deputado::where('id', $id)->with('ultimoStatus.gabinete')->get();
        return response()->json($deputado);
    }

    public function getByCodigoApi($codigo) {
        $deputado = Deputado::where('codigo_api', $codigo)->with('ultimoStatus.gabinete')->get();
        return response()->json($deputado);
    }

}

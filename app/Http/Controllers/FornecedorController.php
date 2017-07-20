<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Fornecedor;

class FornecedorController extends Controller
{

    public function __construct()
    {
        //
    }

    public function list() {
        $fornecedores = Fornecedor::all();
        return response()->json($fornecedores);
    }

    public function get($id) {
        $fornecedor = Fornecedor::findOrFail($id);
        return response()->json($fornecedor);
    }

    public function findByCpfCnpj($cpfCnpj) {
        $fornecedor = Fornecedor::where('cpf_cnpj', $cpfCnpj)->get();
        return response()->json($fornecedor);
    }

}

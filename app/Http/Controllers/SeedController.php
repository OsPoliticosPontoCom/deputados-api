<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Deputado;
use App\Status;
use App\Gabinete;
use App\Despesa;
use App\TipoDespesa;
use App\Fornecedor;

class SeedController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function seedDeputados() {

        $deputados = self::searchDeputados();
        foreach ($deputados as $key => $deputado) {

            $novoDeputado = new Deputado();
            $novoDeputado->codigo_api = $deputado->id;
            $novoDeputado->uri = $deputado->uri;
            $novoDeputado->nome_civil = $deputado->nomeCivil;
            $novoDeputado->cpf = $deputado->cpf;
            $novoDeputado->sexo = $deputado->sexo;
            $novoDeputado->url_website = $deputado->urlWebsite;
            $novoDeputado->data_nascimento = $deputado->dataNascimento;
            $novoDeputado->data_falecimento = $deputado->dataFalecimento;
            $novoDeputado->uf_nascimento = $deputado->ufNascimento;
            $novoDeputado->municipio_nascimento = $deputado->municipioNascimento;
            $novoDeputado->escolaridade = $deputado->escolaridade;

            $novoDeputado->save();

            if ($novoDeputado->id != null) {

                $status = new Status();
                $status->uri = $deputado->ultimoStatus->uri;
                $status->nome = $deputado->ultimoStatus->nome;
                $status->sigla_partido = $deputado->ultimoStatus->siglaPartido;
                $status->uri_partido = $deputado->ultimoStatus->uriPartido;
                $status->sigla_uf = $deputado->ultimoStatus->siglaUf;
                $status->id_legislatura = $deputado->ultimoStatus->idLegislatura;
                $status->url_foto = $deputado->ultimoStatus->urlFoto;
                $status->data = $deputado->ultimoStatus->data;
                $status->nome_eleitoral = $deputado->ultimoStatus->nomeEleitoral;
                $status->situacao = $deputado->ultimoStatus->situacao;
                $status->condicao_eleitoral = $deputado->ultimoStatus->condicaoEleitoral;
                $status->descricao_status = $deputado->ultimoStatus->descricaoStatus;
                $status->deputado_id = $novoDeputado->id;
                $status->save();

                if ($status->id != null) {
                    $gabinete = new Gabinete();
                    $gabinete->nome = $deputado->ultimoStatus->gabinete->nome;
                    $gabinete->predio = $deputado->ultimoStatus->gabinete->predio;
                    $gabinete->sala = $deputado->ultimoStatus->gabinete->sala;
                    $gabinete->andar = $deputado->ultimoStatus->gabinete->andar;
                    $gabinete->telefone = $deputado->ultimoStatus->gabinete->telefone;
                    $gabinete->email = $deputado->ultimoStatus->gabinete->email;
                    $gabinete->status_id = $status->id;

                    $gabinete->save();
                }

            }

        }

        return response()->json(["status" => "ok"]);

    }

    public function searchDeputados() {
        $client = new Client();
        $res = $client->request('GET', 'https://dadosabertos.camara.leg.br/api/v2/deputados', [
            'query' => [
                'pagina' => '1',
                'itens' => '100',
                'ordem' => 'ASC',
                'ordenarPor' => 'nome'
            ],
            'headers' => [
            'Accept' => 'application/json'
        ]]);

        $content = json_decode($res->getBody()->getContents());

        $deputados = $content->dados;

        $deputadosArray = [];

        foreach ($deputados as $key => $deputado) {
            $res = $client->request('GET', 'https://dadosabertos.camara.leg.br/api/v2/deputados/' . $deputado->id, [
                'headers' => [
                'Accept' => 'application/json'
            ]]);
            $newContent = json_decode($res->getBody()->getContents());
            array_push($deputadosArray, $newContent->dados);
        }

        if ($content->links[1]->rel == "next") {
            $iterate = true;
        } else {
            $iterate = false;
        }

        while($iterate) {
            $client = new Client();
            $res = $client->request('GET', $content->links[1]->href, [
                'headers' => [
                'Accept' => 'application/json'
            ]]);

            $content = json_decode($res->getBody()->getContents());

            $deputados = $content->dados;

            foreach ($deputados as $key => $deputado) {
                $res = $client->request('GET', 'https://dadosabertos.camara.leg.br/api/v2/deputados/' . $deputado->id, [
                    'headers' => [
                    'Accept' => 'application/json'
                ]]);
                $newContent = json_decode($res->getBody()->getContents());
                array_push($deputadosArray, $newContent->dados);
            }
            if (!($content->links[1]->rel == "next")) {
                $iterate = false;
            }
        }

        return $deputadosArray;
    }

    public function seedDespesas() {

        $deputados = Deputado::all();
        foreach ($deputados as $key => $deputado) {
            $arrayDespesas = self::searchDespesas($deputado->codigo_api);

            foreach ($arrayDespesas as $key => $despesa) {
                $tipoDespesa = TipoDespesa::where('descricao', $despesa->tipoDespesa)->first();
                if ($tipoDespesa == null) {
                    $tipoDespesa = new TipoDespesa();
                    $tipoDespesa->descricao = $despesa->tipoDespesa;
                    $tipoDespesa->save();
                }
                if ($tipoDespesa != null && $tipoDespesa->id != null) {
                    $fornecedor = Fornecedor::where('cpf_cnpj', $despesa->cnpjCpfFornecedor)->first();
                    if ($fornecedor == null) {
                        $fornecedor = new Fornecedor();
                        $fornecedor->nome_fornecedor = $despesa->nomeFornecedor;
                        $fornecedor->cpf_cnpj = $despesa->cnpjCpfFornecedor;
                        $fornecedor->save();
                    }

                    if ($fornecedor != null && $fornecedor->id != null) {
                        $novaDespesa = new Despesa();
                        $novaDespesa->ano = $despesa->ano;
                        $novaDespesa->mes = $despesa->mes;
                        $novaDespesa->id_documento = $despesa->idDocumento;
                        $novaDespesa->tipo_documento = $despesa->tipoDocumento;
                        $novaDespesa->data_documento = $despesa->dataDocumento;
                        $novaDespesa->num_documento = $despesa->numDocumento;
                        $novaDespesa->valor_documento = floatval($despesa->valorDocumento);
                        $novaDespesa->url_documento = $despesa->urlDocumento;
                        $novaDespesa->valor_liquido = floatval($despesa->valorLiquido);
                        $novaDespesa->valor_glosa = floatval($despesa->valorGlosa);
                        $novaDespesa->num_ressarcimento = $despesa->numRessarcimento;
                        $novaDespesa->id_lote = $despesa->idLote;
                        $novaDespesa->parcela = $despesa->parcela;
                        $novaDespesa->deputado_id = $deputado->id;
                        $novaDespesa->tipos_despesas_id = $tipoDespesa->id;
                        $novaDespesa->save();
                        if ($novaDespesa->id != null) {
                            $novaDespesa->fornecedores()->attach([$fornecedor->id]);
                        }

                    }

                }

            }

        }

        return response()->json(["status" => "ok"]);

    }

    public function searchDespesas($id) {
        $client = new Client();
        $res = $client->request('GET', 'https://dadosabertos.camara.leg.br/api/v2/deputados/' . $id . '/despesas', [
            'query' => [
                'pagina' => '1',
                'itens' => '15'
            ],
            'headers' => [
            'Accept' => 'application/json'
        ]]);

        $content = json_decode($res->getBody()->getContents());

        $despesas = $content->dados;

        $despesasArray = [];

        foreach ($despesas as $key => $despesa) {
            array_push($despesasArray, $despesa);
        }

        if ($content->links[1]->rel == "next") {
            $iterate = true;
        } else {
            $iterate = false;
        }

        while($iterate) {
            $client = new Client();
            $res = $client->request('GET', $content->links[1]->href, [
                'headers' => [
                'Accept' => 'application/json'
            ]]);

            $content = json_decode($res->getBody()->getContents());

            $despesas = $content->dados;

            foreach ($despesas as $key => $despesa) {
                array_push($despesasArray, $despesa);
            }
            if (!($content->links[1]->rel == "next")) {
                $iterate = false;
            }
        }

        return $despesasArray;
    }

}

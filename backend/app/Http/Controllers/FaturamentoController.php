<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\Faturamento;
use App\Models\Pessoa;
use App\Models\Veiculo;
use App\TollBox\ExceptionLogSchema;
use App\TollBox\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class FaturamentoController extends Controller
{
    public function validated($type, $request)
    {
        if ($type == "store") {
            $request->validate(
                [
                    'pessoa_motorista_id' => ['nullable'],
                    'veiculo_id' => ['nullable'],
                    'data_embarque' => ['nullable', 'date'],
                    'data_descargar' => ['nullable', 'date'],
                    'origem_cidade_id' => ['nullable'],
                    'origem_local' => ['nullable', 'max:65535', 'string'],
                    'destino_cidade_id' => ['nullable'],
                    'destino_local' => ['nullable', 'max:65535', 'string'],
                    'pessoa_cliente_id' => ['required'],
                    'danfe' => ['nullable'],
                    'peso' => ['nullable', 'numeric'],
                    'valor_acerto_motorista' => ['nullable', 'numeric'],
                    'valor_total' => ['required', 'numeric'],
                    'DMT' => ['nullable', 'max:255', 'string'],
                    'carga' => ['nullable', 'max:65535', 'string'],
                    'descritivo' => ['nullable', 'max:65535', 'string'],
                ]
            );
        } else {
            $request->validate([
                'pessoa_motorista_id' => ['nullable'],
                'veiculo_id' => ['nullable'],
                'data_embarque' => ['nullable', 'date'],
                'data_descargar' => ['nullable', 'date'],
                'origem_cidade_id' => ['nullable'],
                'origem_local' => ['nullable', 'max:65535', 'string'],
                'destino_cidade_id' => ['nullable'],
                'destino_local' => ['nullable', 'max:65535', 'string'],
                'pessoa_cliente_id' => ['required'],
                'danfe' => ['nullable'],
                'peso' => ['nullable', 'numeric'],
                'valor_acerto_motorista' => ['required', 'numeric'],
                'valor_total' => ['required', 'numeric'],
                'DMT' => ['nullable', 'max:255', 'string'],
                'carga' => ['nullable', 'max:65535', 'string'],
                'descritivo' => ['nullable', 'max:65535', 'string'],
            ]);
        }
        return $request->only(["pessoa_motorista_id", "veiculo_id", "data_embarque", "origem_cidade_id", "origem_local", "destino_cidade_id", "destino_local", "pessoa_cliente_id", "danfe", "peso", "valor_acerto_motorista", "valor_total", "DMT", "carga", "descritivo", "data_descargar"]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {

        $search = $request->get("search", "");
        if ($search == null) {
            $search = "";
        }
        $faturamentos = Faturamento::search($search)
            ->select(
                'faturamentos.id',
                DB::raw('concat(veiculos.id,"-",veiculos.nome," Placa: ",veiculos.placa," Cor: ",veiculos.cor) as veiculo_id'),
                'motoristas.nome  as pessoa_motorista_id',
                DB::raw("DATE_FORMAT(faturamentos.data_embarque, '%d/%m/%Y') as data_embarque"),
                DB::raw("DATE_FORMAT(faturamentos.data_descargar, '%d/%m/%Y') as data_descargar"),
                DB::raw('concat(origens.nome," (",origens.uf,")") as origem_cidade_id'),
                DB::raw('concat(destinos.nome," (",destinos.uf,")") as destino_cidade_id'),
                DB::raw('concat(clientes.nome," (",clientes.cpf_cnpj,")") as pessoa_cliente_id'),
                DB::raw('REPLACE(faturamentos.valor_total, ".", ",") as valor_total')


            )
            ->leftJoin('veiculos', 'faturamentos.veiculo_id', 'veiculos.id')
            ->leftJoin('pessoas as motoristas', 'faturamentos.pessoa_motorista_id', 'motoristas.id')
            ->leftJoin('cidades as origens', 'faturamentos.origem_cidade_id', 'origens.id')
            ->leftJoin('cidades as destinos', 'faturamentos.destino_cidade_id', 'destinos.id')
            ->leftJoin('pessoas as clientes', 'faturamentos.pessoa_cliente_id', 'clientes.id')
            ->orderBy("faturamentos.id", "desc")
            ->paginate(1000);

        return response()->json($faturamentos);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();

            $validated = $this->validated("store", $request);

            $faturamento = Faturamento::create($validated);
            $this->addContasPagar($faturamento);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            ExceptionLogSchema::error($e);
            return response()->json(['message' => $e->getMessage()], 422);

        }
        return response()->json($faturamento);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $faturamento = Faturamento::find($id);

        return response()->json($faturamento);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
                $id
    ): JsonResponse
    {
        try {
            DB::beginTransaction();
            $faturamento = Faturamento::find($id);
            $validated = $this->validated("update", $request);

            $faturamento->update($validated);
            $this->updateContasPagar($faturamento);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            ExceptionLogSchema::error($e);
            return response()->json(['message' => $e->getMessage()], 422);

        }

        return response()->json($faturamento);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $faturamento = Faturamento::find($id);
        $faturamento->delete();

        return response()->json(["success" => true, "message" => "Removed success"]);
    }

    private function addContasPagar($faturamento): void
    {
        $pessoa = Pessoa::select(DB::raw('CONCAT(pessoas.id,"-",pessoas.nome, " (",pessoas.cpf_cnpj,")") as pessoa'))->where('pessoas.id', $faturamento['pessoa_cliente_id'])->first();
        $veiculo = Veiculo::select(DB::raw('concat(veiculos.id,"-",veiculos.nome," Placa: ",veiculos.placa," Cor: ",veiculos.cor) as veiculo'))->where('veiculos.id', $faturamento['veiculo_id'])->first();
        $payload = [
            'data_ocorrido' => Carbon::now(),
            'nome' => $pessoa->pessoa,
            'modalidade' => 'receber',
            'natureza_financeira_id' => null,
            'valor' => $faturamento['valor_total'],
            'parcelas' => 1,
            'descritivo' => 'Receber de transporte de Cargas, ID:' . $faturamento['id'] . ' veiculo: ' . $veiculo->veiculo,
            'model_id' => $faturamento['id'],
            'tabela' => 'faturamentos',
        ];
        Conta::create($payload);

    }

    private function updateContasPagar($faturamento)
    {

        $pessoa = Pessoa::select(DB::raw('CONCAT(pessoas.id,"-",pessoas.nome, " (",pessoas.cpf_cnpj,")") as pessoa'))->where('pessoas.id', $faturamento->pessoa_cliente_id)->first();
        $veiculo = Veiculo::select(DB::raw('concat(veiculos.id,"-",veiculos.nome," Placa: ",veiculos.placa," Cor: ",veiculos.cor) as veiculo'))->where('veiculos.id', $faturamento->veiculo_id)->first();
        $payload = [
            'data_ocorrido' => Carbon::now(),
            'nome' => $pessoa->pessoa,
            'valor' => $faturamento->valor_total,
            'descritivo' => 'Receber de transporte de Cargas, ID:' . $faturamento->id . ' veiculo: ' . $veiculo->veiculo,
            'model_id' => $faturamento->id,
            'tabela' => 'faturamentos',

        ];
        $conta = Conta::where('model_id', $faturamento->model_id)->first();

        if (empty($conta)) {
            $payload['model_id'] = $faturamento['id'];
            $payload['tabela'] = 'faturamentos';
            $payload['modalidade'] = 'receber';

            Conta::create($payload);
            return false;
        }
        $conta->update($payload);
        return true;
    }

}

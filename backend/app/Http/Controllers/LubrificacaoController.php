<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\Lubrificacao;
use App\Models\Pessoa;
use App\Models\Veiculo;
use App\TollBox\ExceptionLogSchema;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class LubrificacaoController extends Controller
{
    public function validated($type, $request)
    {
        if ($type == "store") {
            $request->validate(
                [
                    'data' => ['required', 'date'],
                    'pessoa_id' => ['required'],
                    'veiculo_id' => ['required'],
                    'servico' => ['required', 'max:255', 'string'],
                    'km' => ['required'],
                    'valor' => ['nullable', 'numeric'],
                ]
            );
        } else {
            $request->validate([
                'data' => ['required', 'date'],
                'pessoa_id' => ['required'],
                'veiculo_id' => ['required'],
                'servico' => ['required', 'max:255', 'string'],
                'km' => ['required'],
                'valor' => ['nullable', 'numeric'],
            ]);
        }
        return $request->only(["data", "pessoa_id", "veiculo_id", "servico", "km", "valor"]);
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
        $lubrificacaos = Lubrificacao::search($search)
            ->select(
                DB::raw('concat(veiculos.id,"-",veiculos.nome," Placa: ",veiculos.placa," Cor: ",veiculos.cor) as veiculo_id'),
                DB::raw('CONCAT(pessoas.id,"-",pessoas.nome, " (",pessoas.cpf_cnpj,")") as pessoa_id'),
                'lubrificacaos.id',
                'lubrificacaos.data',
                'lubrificacaos.servico',
                'lubrificacaos.km',
                DB::raw('REPLACE( lubrificacaos.valor, ".", ",") as valor'),
                'lubrificacaos.created_at',
                'lubrificacaos.updated_at',
            )
            ->leftJoin('pessoas', 'lubrificacaos.pessoa_id', 'pessoas.id')
            ->leftJoin('veiculos', 'lubrificacaos.veiculo_id', 'veiculos.id')
            ->paginate(1000);

        return response()->json($lubrificacaos);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $validated = $this->validated("store", $request);

            $lubrificacao = Lubrificacao::create($validated);

            $this->addContasPagar($lubrificacao);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            ExceptionLogSchema::error($e);
            return response()->json(['message' => $e->getMessage()], 422);

        }


        return response()->json($lubrificacao);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $lubrificacao = Lubrificacao::find($id);

        return response()->json($lubrificacao);
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
            $lubrificacao = Lubrificacao::find($id);
            $validated = $this->validated("update", $request);

            $lubrificacao->update($validated);

            $this->updateContasPagar($lubrificacao);
            DB::commit();
            return response()->json($lubrificacao);


        } catch (\Exception $e) {
            DB::rollBack();
            ExceptionLogSchema::error($e);
            return response()->json(['message' => $e->getMessage()], 422);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $lubrificacao = Lubrificacao::find($id);
        $lubrificacao->delete();
        Conta::where('model_id', $id)->delete();

        return response()->json(["success" => true, "message" => "Removed success"]);
    }

    private function addContasPagar($lubrificacao): void
    {
        $pessoa = Pessoa::select(DB::raw('CONCAT(pessoas.id,"-",pessoas.nome, " (",pessoas.cpf_cnpj,")") as pessoa'))->where('pessoas.id', $lubrificacao['pessoa_id'])->first();
        $veiculo = Veiculo::select(DB::raw('concat(veiculos.id,"-",veiculos.nome," Placa: ",veiculos.placa," Cor: ",veiculos.cor) as veiculo'))->where('veiculos.id', $lubrificacao['veiculo_id'])->first();
        $payload = [
            'data_ocorrido' => Carbon::now(),
            'nome' => $pessoa->pessoa,
            'modalidade' => 'pagar',
            'natureza_financeira_id' => null,
            'valor' => $lubrificacao['valor'],
            'parcelas' => 1,
            'descritivo' => 'Contas a pagar de lubrificação , ID:' . $lubrificacao['id'] . ',' . ' Veiculo: ' . $veiculo->veiculo . ' Serviço: ' . $lubrificacao['servico'],
            'model_id' => $lubrificacao['id'],
            'tabela' => 'lubrificacaos',
        ];

        Conta::create($payload);

    }

    private function updateContasPagar($lubrificacao): bool
    {
        $pessoa = Pessoa::select(DB::raw('CONCAT(pessoas.id,"-",pessoas.nome, " (",pessoas.cpf_cnpj,")") as pessoa'))->where('pessoas.id', $lubrificacao->pessoa_id)->first();
        $veiculo = Veiculo::select(DB::raw('concat(veiculos.id,"-",veiculos.nome," Placa: ",veiculos.placa," Cor: ",veiculos.cor) as veiculo'))->where('veiculos.id', $lubrificacao->veiculo_id)->first();
        $payload = [
            'data_ocorrido' => Carbon::now(),
            'nome' => $pessoa->pessoa,
            'modalidade' => 'pagar',
            'natureza_financeira_id' => null,
            'valor' => $lubrificacao->valor,
            'parcelas' => 1,
            'descritivo' => 'Contas a pagar de lubrificação , ID:' . $lubrificacao->id . ',' . ' Veiculo: ' . $veiculo->veiculo . ' Serviço: ' . $lubrificacao->servico,
            'tabela' => 'lubrificacaos',
        ];
        $conta = Conta::where('model_id', $lubrificacao->id)->first();
        if (empty($conta)) {
            $payload['model_id'] = $lubrificacao['id'];
            $payload['tabela'] = 'lubrificacaos';

            Conta::create($payload);
            return false;
        }
        $conta->update($payload);
        return true;


    }

}

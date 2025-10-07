<?php

namespace App\Http\Controllers;

use App\Models\AbastecimentoVeiculo;
use App\Models\Conta;
use App\Models\Pessoa;
use App\Models\Veiculo;
use App\TollBox\ExceptionLogSchema;
use App\TollBox\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class AbastecimentoVeiculoController extends Controller
{
    public function validated($type, $request)
    {
        if ($type == "store") {
            $request->validate(
                [
                    'veiculo_id' => ['required'],
                    'quilometragem' => ['required'],
                    'litros' => ['required', 'numeric'],
                    'valor' => ['required', 'numeric'],
                    'pessoa_id' => ['nullable', 'numeric'],
                    'numero_nota' => ['nullable', 'numeric'],
                    'tipo' => ['required'],
                    'descritivo' => ['nullable'],
                ]
            );
        } else {
            $request->validate([
                'veiculo_id' => ['required'],
                'quilometragem' => ['required'],
                'litros' => ['required', 'numeric'],
                'valor' => ['required', 'numeric'],
                'pessoa_id' => ['nullable', 'numeric'],
                'numero_nota' => ['nullable', 'numeric'],
                'tipo' => ['required'],
                'descritivo' => ['nullable'],
            ]);
        }
        return $request->only(["veiculo_id", "quilometragem", "litros", "valor", "pessoa_id", "numero_nota", "tipo", "descritivo"]);
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
        $abastecimento_veiculos = AbastecimentoVeiculo::search($search)
            ->select(
                "abastecimento_veiculos.id",
                DB::raw('concat(veiculos.id,"-",veiculos.nome," Placa: ",veiculos.placa," Cor: ",veiculos.cor) as veiculo_id'),
                "abastecimento_veiculos.quilometragem",
                "abastecimento_veiculos.tipo",
                DB::raw('REPLACE(FORMAT(abastecimento_veiculos.litros, 2), ".", ",") as litros'),
                DB::raw('REPLACE(FORMAT(abastecimento_veiculos.valor, 2), ".", ",") as valor'),
                DB::raw('CONCAT(pessoas.id,"-",pessoas.nome, " (",pessoas.cpf_cnpj,")") as pessoa_id')
            )
            ->leftJoin('pessoas', 'abastecimento_veiculos.pessoa_id', 'pessoas.id')
            ->join('veiculos', 'abastecimento_veiculos.veiculo_id', 'veiculos.id')
            ->orderBy("abastecimento_veiculos.id", "desc")
            ->paginate(1000);

        return response()->json($abastecimento_veiculos);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $validated = $this->validated("store", $request);

            $abastecimento_veiculo = AbastecimentoVeiculo::create($validated);

            $this->addContasPagar($abastecimento_veiculo);

            DB::commit();

            return response()->json($abastecimento_veiculo);
        } catch (\Exception $e) {
            DB::rollBack();
            ExceptionLogSchema::error($e);
            return response()->json(['message' => $e->getMessage()], 422);

        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $abastecimento_veiculo = AbastecimentoVeiculo::find($id);

        return response()->json($abastecimento_veiculo);
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
            $abastecimento_veiculo = AbastecimentoVeiculo::find($id);
            $validated = $this->validated("update", $request);

            $abastecimento_veiculo->update($validated);

            $abastecimento_veiculo = AbastecimentoVeiculo::find($id);
            $this->updateContasPagar($abastecimento_veiculo);
            DB::commit();

            return response()->json($abastecimento_veiculo);
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
        $abastecimento_veiculo = AbastecimentoVeiculo::find($id);
        $abastecimento_veiculo->delete();
        Conta::where('model_id', $id)->delete();

        return response()->json(["success" => true, "message" => "Removed success"]);
    }

    private function addContasPagar($abastecimento_veiculo): void
    {

        $pessoa = Pessoa::select(DB::raw('CONCAT(pessoas.id,"-",pessoas.nome, " (",pessoas.cpf_cnpj,")") as pessoa'))->where('pessoas.id', $abastecimento_veiculo['pessoa_id'])->first();
        $veiculo = Veiculo::select(DB::raw('concat(veiculos.id,"-",veiculos.nome," Placa: ",veiculos.placa," Cor: ",veiculos.cor) as veiculo'))->where('veiculos.id', $abastecimento_veiculo['veiculo_id'])->first();
        $payload = [
            'data_ocorrido' => Carbon::now(),
            'nome' => $pessoa->pessoa,
            'modalidade' => 'pagar',
            'natureza_financeira_id' => null,
            'valor' => $abastecimento_veiculo['valor'] * $abastecimento_veiculo['litros'],
            'parcelas' => 1,
            'descritivo' => 'Contas a pagar de abastecimento, ID: ' . $abastecimento_veiculo['id'] . ',' . ' Veiculo: ' . $veiculo->veiculo . ' Combustivel: ' . $abastecimento_veiculo['tipo'],
            'model_id' => $abastecimento_veiculo['id'],
            'tabela' => 'abastecimento_veiculos',
        ];
        Conta::create($payload);

    }

    private function updateContasPagar($abastecimento_veiculo): bool
    {

        $pessoa = Pessoa::select(DB::raw('CONCAT(pessoas.id,"-",pessoas.nome, " (",pessoas.cpf_cnpj,")") as pessoa'))->where('pessoas.id', $abastecimento_veiculo->pessoa_id)->first();
        $veiculo = Veiculo::select(DB::raw('concat(veiculos.id,"-",veiculos.nome," Placa: ",veiculos.placa," Cor: ",veiculos.cor) as veiculo'))->where('veiculos.id', $abastecimento_veiculo->veiculo_id)->first();
        $tipoCombustivel = 'diesel';
        if ($tipoCombustivel === 'arla') {
            $tipoCombustivel = 'arla';
        }

        if ($tipoCombustivel === 'diesel_mais_arla') {
            $tipoCombustivel = 'diesel_mais_arla';
        }
        $payload = [
            'data_ocorrido' => Carbon::now(),
            'nome' => $pessoa->pessoa,
            'modalidade' => 'pagar',
            'natureza_financeira_id' => null,
            'valor' => $abastecimento_veiculo->valor * $abastecimento_veiculo->litros,
            'parcelas' => 1,
            'descritivo' => 'Contas a pagar de abastecimento, ID: ' . $abastecimento_veiculo->id . ',' . ' Veiculo: ' . $veiculo->veiculo . ' Combustivel: ' . $tipoCombustivel,
            'tabela' => 'abastecimento_veiculos',
        ];
        $conta = Conta::where('model_id', $abastecimento_veiculo->id)->first();
        if(empty($conta)){
            $payload['model_id'] = $abastecimento_veiculo['id'];
            $payload['tabela'] = 'abastecimento_veiculos';

            Conta::create($payload);
            return false;
        }
        $conta->update($payload);
        return true;

    }

}

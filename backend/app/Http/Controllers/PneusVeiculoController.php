<?php

namespace App\Http\Controllers;

use App\TollBox\ExceptionLogSchema;
use App\Models\Conta;
use App\Models\Pessoa;
use App\Models\PneusVeiculo;
use App\Models\Veiculo;
use App\TollBox\Helper;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class PneusVeiculoController extends Controller
{
    public function validated($type, $request)
    {
        if ($type == "store") {
            $request->validate(
                [
                    'veiculo_id' => ['required'],
                    'quilometragem' => ['required'],
                    'quantidade' => ['required'],
                    'valor' => ['required', 'numeric'],
                    'aro' => ['nullable', 'numeric'],
                    'marca' => ['nullable'],
                    'pessoa_id' => ['nullable'],
                ]
            );
        } else {
            $request->validate([
                'veiculo_id' => ['required'],
                'quilometragem' => ['required'],
                'quantidade' => ['required'],
                'valor' => ['required', 'numeric'],
                'aro' => ['nullable', 'numeric'],
                'marca' => ['nullable'],
                'pessoa_id' => ['nullable'],
            ]);
        }
        return $request->only(["veiculo_id", "quilometragem", "quantidade", "valor", 'aro', 'marca', 'pessoa_id']);
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
        $pneus_veiculos = PneusVeiculo::search($search)
            ->select(
                "pneus_veiculos.id",
                DB::raw('concat(veiculos.id, "-", veiculos.nome, " Placa: ", veiculos.placa, " Cor: ", veiculos.cor) as veiculo_id'),
                "pneus_veiculos.quilometragem",
                "pneus_veiculos.quantidade",
                DB::raw('REPLACE(pneus_veiculos.valor, ".", ",") as valor'),
                DB::raw('CONCAT(pessoas.id,"-",pessoas.nome, " (",pessoas.cpf_cnpj,")") as pessoa_id')
            )
            ->leftJoin('pessoas', 'pneus_veiculos.pessoa_id', 'pessoas.id')
            ->join('veiculos', 'pneus_veiculos.veiculo_id', 'veiculos.id')
            ->orderBy("pneus_veiculos.id", "desc")
            ->paginate(1000);


        return response()->json($pneus_veiculos);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {

        $validated = $this->validated("store", $request);
        try {
            DB::beginTransaction();
            $pneus_veiculo = PneusVeiculo::create($validated);

            $this->addContasPagar($pneus_veiculo);
            DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            ExceptionLogSchema::error($e);
            return response()->json(['message'=> $e->getMessage()],422);

        }


        return response()->json($pneus_veiculo);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $pneus_veiculo = PneusVeiculo::find($id);

        return response()->json($pneus_veiculo);
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
            $pneus_veiculo = PneusVeiculo::find($id);
            $validated = $this->validated("update", $request);

            $pneus_veiculo->update($validated);
            $pneus_veiculo = PneusVeiculo::find($id);
            $this->updateContasPagar($pneus_veiculo);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            ExceptionLogSchema::error($e);
            return response()->json(['message' => $e->getMessage()], 422);

        }

        return response()->json($pneus_veiculo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $pneus_veiculo = PneusVeiculo::find($id);
        $pneus_veiculo->delete();
        Conta::where('model_id', $id)->delete();

        return response()->json(["success" => true, "message" => "Removed success"]);
    }

    private function addContasPagar($pneus_veiculo):void
    {
        $pessoa = Pessoa::select(DB::raw('CONCAT(pessoas.id,"-",pessoas.nome, " (",pessoas.cpf_cnpj,")") as pessoa'))->where('pessoas.id',$pneus_veiculo['pessoa_id'])->first();
        $veiculo = Veiculo::select(DB::raw('concat(veiculos.id,"-",veiculos.nome," Placa: ",veiculos.placa," Cor: ",veiculos.cor) as veiculo'))->where('veiculos.id',$pneus_veiculo['veiculo_id'])->first();
        $payload = [
            'data_ocorrido' => Carbon::now(),
            'nome' => $pessoa->pessoa,
            'modalidade' => 'pagar',
            'natureza_financeira_id' => null,
            'valor' => $pneus_veiculo['quantidade']*$pneus_veiculo['valor'],
            'parcelas' => 1,
            'descritivo' => 'Contas a pagar de troca de pneus, ID:'.$pneus_veiculo['id'].' '.$pneus_veiculo['quantidade'].' pneu(s) , aro ' . $pneus_veiculo['aro'].' marca: '.$pneus_veiculo['marca']. ' veiculo: '. $veiculo->veiculo ,
            'model_id' => $pneus_veiculo['id'],
            'tabela' => 'pneus_veiculos',
        ];
        Conta::create($payload);

    }

    private function updateContasPagar( $pneus_veiculo)
    {

        $pessoa = Pessoa::select(DB::raw('CONCAT(pessoas.id,"-",pessoas.nome, " (",pessoas.cpf_cnpj,")") as pessoa'))->where('pessoas.id',$pneus_veiculo->pessoa_id)->first();
        $veiculo = Veiculo::select(DB::raw('concat(veiculos.id,"-",veiculos.nome," Placa: ",veiculos.placa," Cor: ",veiculos.cor) as veiculo'))->where('veiculos.id',$pneus_veiculo->veiculo_id)->first();
        $payload = [
            'data_ocorrido' => Carbon::now(),
            'nome' => $pessoa->pessoa,
            'modalidade' => 'pagar',
            'natureza_financeira_id' => null,
            'valor' => $pneus_veiculo->quantidade*$pneus_veiculo->valor,
            'parcelas' => 1,
            'descritivo' => 'Contas a pagar de troca de pneus, ID:'.$pneus_veiculo->id.' '.$pneus_veiculo->quantidade.' pneu(s) , aro ' . $pneus_veiculo->aro.' marca: '.$pneus_veiculo->marca. ' veiculo: '. $veiculo->veiculo ,
            'model_id' => $pneus_veiculo->id,
            'tabela' => 'pneus_veiculos',
        ];
        $conta = Conta::where('model_id',$pneus_veiculo->model_id)->first();
        if(empty($conta)){
            $payload['model_id'] = $pneus_veiculo['id'];
            $payload['tabela'] = 'pneus_veiculos';

            Conta::create($payload);
            return false;
        }
        $conta->update($payload);
        return true;
    }

}

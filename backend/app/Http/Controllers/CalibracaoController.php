<?php

namespace App\Http\Controllers;

use App\Models\Calibracao;
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


class CalibracaoController extends Controller
{
    public function validated($type,$request){
        if($type=="store"){
        $request->validate(
        [
            'data'=>['required','date'],
            'pessoa_id'=>['required'],
            'veiculo_id'=>['required'],
            'servico'=>['required','max:255','string'],
            'km'=>['required'],
            'valor'=>['nullable'],
        ]
        );
    }else{
        $request->validate([
            'data'=>['required','date'],
            'pessoa_id'=>['required'],
            'veiculo_id'=>['required'],
            'servico'=>['required','max:255','string'],
            'km'=>['required'],
            'valor'=>['nullable','numeric'],
        ]);
    }
        return $request->only(["data","pessoa_id","veiculo_id","servico","km","valor"]);
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
        $calibracaos = Calibracao::search($search)
            ->select(
                'calibracaos.id',
                'calibracaos.data',
                DB::raw('concat(veiculos.id,"-",veiculos.nome," Placa: ",veiculos.placa," Cor: ",veiculos.cor) as veiculo_id'),
                DB::raw('CONCAT(pessoas.id,"-",pessoas.nome, " (",pessoas.cpf_cnpj,")") as pessoa_id'),
                'calibracaos.servico',
                'calibracaos.km',
                DB::raw('REPLACE( calibracaos.valor, ".", ",") as valor'),
               'calibracaos.created_at',
                'calibracaos.updated_at',
            )
            ->leftJoin('pessoas','calibracaos.pessoa_id','pessoas.id')
            ->leftJoin('veiculos','calibracaos.veiculo_id','veiculos.id')
            ->paginate(1000);

        return response()->json($calibracaos);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
        DB::beginTransaction();

        $validated = $this->validated("store",$request);

        $calibracao = Calibracao::create($validated);

        $this->addContasPagar($calibracao);

        DB::commit();
        }catch (\Exception $e){
            DB::rollBack();
            ExceptionLogSchema::error($e);
            return response()->json(['message'=> $e->getMessage()],422);

        }

         return response()->json($calibracao);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id):JsonResponse
    {
        $calibracao = Calibracao::find($id);

       return response()->json($calibracao);
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
            $calibracao = Calibracao::find($id);
            $validated = $this->validated("update", $request);

            $calibracao->update($validated);
            $calibracao = Calibracao::find($id);
            $this->updateContasPagar($calibracao);
        } catch (\Exception $e) {
            DB::rollBack();
            ExceptionLogSchema::error($e);
            return response()->json(['message' => $e->getMessage()], 422);

        }

        return response()->json($calibracao);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $calibracao = Calibracao::find($id);
        $calibracao->delete();
        Conta::where('model_id', $id)->delete();

       return response()->json(["success"=>true,"message"=>"Removed success"]);
    }

    private function addContasPagar($calibracao):void
    {

        $pessoa = Pessoa::select(DB::raw('CONCAT(pessoas.id,"-",pessoas.nome, " (",pessoas.cpf_cnpj,")") as pessoa'))->where('pessoas.id',$calibracao['pessoa_id'])->first();
        $veiculo = Veiculo::select(DB::raw('concat(veiculos.id,"-",veiculos.nome," Placa: ",veiculos.placa," Cor: ",veiculos.cor) as veiculo'))->where('veiculos.id',$calibracao['veiculo_id'])->first();
        $payload = [
            'data_ocorrido' => Carbon::now(),
            'nome' => $pessoa->pessoa,
            'modalidade' => 'pagar',
            'natureza_financeira_id' => null,
            'valor' => $calibracao['valor'],
            'parcelas' => 1,
            'descritivo' => 'Contas a pagar de Calibração , ID:'.$calibracao['id'].','. ' Veiculo: '. $veiculo->veiculo .' Serviço: '.$calibracao['servico'],
            'model_id' => $calibracao['id'],
            'tabela' => 'calibracaos',
        ];
        Conta::create($payload);

    }
    private function updateContasPagar($calibracao):bool
    {
        $pessoa = Pessoa::select(DB::raw('CONCAT(pessoas.id,"-",pessoas.nome, " (",pessoas.cpf_cnpj,")") as pessoa'))->where('pessoas.id',$calibracao->pessoa_id)->first();
        $veiculo = Veiculo::select(DB::raw('concat(veiculos.id,"-",veiculos.nome," Placa: ",veiculos.placa," Cor: ",veiculos.cor) as veiculo'))->where('veiculos.id',$calibracao->veiculo_id)->first();
        $payload = [
            'data_ocorrido' => Carbon::now(),
            'nome' => $pessoa->pessoa,
            'modalidade' => 'pagar',
            'natureza_financeira_id' => null,
            'valor' => $calibracao->valor,
            'parcelas' => 1,
            'descritivo' => 'Contas a pagar de calibração , ID:'.$calibracao->id.','. ' Veiculo: '. $veiculo->veiculo .' Serviço: '.$calibracao->servico,
            'tabela' => 'calibracaos',
        ];
        $conta = Conta::where('model_id',$calibracao->id)->first();
        if(empty($conta)){
            $payload['model_id'] = $calibracao['id'];
            $payload['tabela'] = 'calibracaos';

            Conta::create($payload);
            return false;
        }
        $conta->update($payload);
        return true;

    }

}

<?php

namespace App\Http\Controllers;

use App\Models\Calibracao;
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
        ]
        );
    }else{
        $request->validate([
            'data'=>['required','date'],
            'pessoa_id'=>['required'],
            'veiculo_id'=>['required'],
            'servico'=>['required','max:255','string'],
            'km'=>['required'],
        ]);
    }
        return $request->only(["data","pessoa_id","veiculo_id","servico","km"]);
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

        $validated = $this->validated("store",$request);

        $calibracao = Calibracao::create($validated);

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
    ): JsonResponse{

        $calibracao = Calibracao::find($id);
        $validated = $this->validated("update",$request);

        $calibracao->update($validated);

         return response()->json($calibracao);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $calibracao = Calibracao::find($id);
        $calibracao->delete();

       return response()->json(["success"=>true,"message"=>"Removed success"]);
    }

}

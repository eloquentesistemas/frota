<?php

namespace App\Http\Controllers;

use App\Models\PneusVeiculo;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class PneusVeiculoController extends Controller
{
    public function validated($type,$request){
        if($type=="store"){
        $request->validate(
        [
            'veiculo_id'=>['required'],
            'quilometragem'=>['required'],
            'quantidade'=>['required'],
            'valor'=>['required','numeric'],
            'aro'=>['nullable','numeric'],
            'marca'=>['nullable'],
            'pessoa_id'=>['nullable'],
        ]
        );
    }else{
        $request->validate([
            'veiculo_id'=>['required'],
            'quilometragem'=>['required'],
            'quantidade'=>['required'],
            'valor'=>['required','numeric'],
            'aro'=>['nullable','numeric'],
            'marca'=>['nullable'],
            'pessoa_id'=>['nullable'],
        ]);
    }
        return $request->only(["veiculo_id","quilometragem","quantidade","valor",'aro','marca','pessoa_id']);
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

        $validated = $this->validated("store",$request);

        $pneus_veiculo = PneusVeiculo::create($validated);

         return response()->json($pneus_veiculo);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id):JsonResponse
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
    ): JsonResponse{

        $pneus_veiculo = PneusVeiculo::find($id);
        $validated = $this->validated("update",$request);

        $pneus_veiculo->update($validated);

         return response()->json($pneus_veiculo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $pneus_veiculo = PneusVeiculo::find($id);
        $pneus_veiculo->delete();

       return response()->json(["success"=>true,"message"=>"Removed success"]);
    }

}

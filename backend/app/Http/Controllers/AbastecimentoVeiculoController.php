<?php

namespace App\Http\Controllers;

use App\Models\AbastecimentoVeiculo;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class AbastecimentoVeiculoController extends Controller
{
    public function validated($type,$request){
        if($type=="store"){
        $request->validate(
        [
            'veiculo_id'=>['required'],
            'quilometragem'=>['required'],
            'litros'=>['required','numeric'],
            'valor'=>['required','numeric'],
        ]
        );
    }else{
        $request->validate([
            'veiculo_id'=>['required'],
            'quilometragem'=>['required'],
            'litros'=>['required','numeric'],
            'valor'=>['required','numeric'],
        ]);
    }
        return $request->only(["veiculo_id","quilometragem","litros","valor"]);
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
                "abastecimento_veiculos.litros",
                DB::raw('REPLACE(FORMAT(abastecimento_veiculos.valor, 2), ".", ",") as valor')
            )
            ->join('veiculos', 'abastecimento_veiculos.veiculo_id', 'veiculos.id')
        ->paginate(1000);

        return response()->json($abastecimento_veiculos);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {

        $validated = $this->validated("store",$request);

        $abastecimento_veiculo = AbastecimentoVeiculo::create($validated);

         return response()->json($abastecimento_veiculo);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id):JsonResponse
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
    ): JsonResponse{

        $abastecimento_veiculo = AbastecimentoVeiculo::find($id);
        $validated = $this->validated("update",$request);

        $abastecimento_veiculo->update($validated);

         return response()->json($abastecimento_veiculo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $abastecimento_veiculo = AbastecimentoVeiculo::find($id);
        $abastecimento_veiculo->delete();

       return response()->json(["success"=>true,"message"=>"Removed success"]);
    }

}

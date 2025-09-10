<?php

namespace App\Http\Controllers;

use App\Models\PessoaVeiculo;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use function Laravel\Prompts\select;


class PessoaVeiculoController extends Controller
{
    public function validated($type,$request){
        if($type=="store"){
        $request->validate(
        [
            'pessoa_id'=>['required'],
            'veiculo_id'=>['required'],
        ]
        );
    }else{
        $request->validate([
            'pessoa_id'=>['required'],
            'veiculo_id'=>['required'],
        ]);
    }
        return $request->only(["pessoa_id","veiculo_id"]);
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
        $pessoa_veiculos = PessoaVeiculo::search($search)
        ->selectRaw('concat(pessoas.id,"-",pessoas.nome) as pessoa_id,concat(veiculos.id,"-",veiculos.nome," Placa: ",veiculos.placa," Cor: ",veiculos.cor) as veiculo_id,pessoa_veiculos.id')
        ->join('pessoas','pessoa_veiculos.pessoa_id','pessoas.id')
        ->join('veiculos','pessoa_veiculos.veiculo_id','veiculos.id')
            ->orderBy("pessoa_veiculos.id", "desc")
            ->paginate(1000);

        return response()->json($pessoa_veiculos);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {

        $validated = $this->validated("store",$request);

        $pessoa_veiculo = PessoaVeiculo::create($validated);

         return response()->json($pessoa_veiculo);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id):JsonResponse
    {
        $pessoa_veiculo = PessoaVeiculo::find($id);

       return response()->json($pessoa_veiculo);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
                $id
    ): JsonResponse{

        $pessoa_veiculo = PessoaVeiculo::find($id);
        $validated = $this->validated("update",$request);

        $pessoa_veiculo->update($validated);

         return response()->json($pessoa_veiculo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $pessoa_veiculo = PessoaVeiculo::find($id);
        $pessoa_veiculo->delete();

       return response()->json(["success"=>true,"message"=>"Removed success"]);
    }

}

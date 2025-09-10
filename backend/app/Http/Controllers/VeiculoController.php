<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use App\Models\Veiculo;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class VeiculoController extends Controller
{
    public function validated($type,$request){
        if($type=="store"){
        $request->validate(
        [
            'nome'=>['required','max:255','string'],
            'placa'=>['required','max:8','string'],
            'cor'=>['nullable','max:8'],
            'vencimento_documento'=>['required','date'],
            'ativo'=>['nullable','boolean'],
            'descritivo'=>['nullable','max:65535','string'],
        ]
        );
    }else{
        $request->validate([
            'nome'=>['required','max:255','string'],
            'placa'=>['required','max:8','string'],
            'cor'=>['nullable','max:8'],
            'vencimento_documento'=>['required','date'],
            'ativo'=>['nullable','boolean'],
            'descritivo'=>['nullable','max:65535','string'],
        ]);
    }
        return $request->only(["nome","placa","cor","vencimento_documento","ativo","descritivo"]);
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
        $veiculos = Veiculo::search($search)
            ->select('id',"nome","placa","cor",DB::raw("DATE_FORMAT(vencimento_documento, '%d/%m/%Y') as vencimento_documento"),"ativo","descritivo")
            ->orderBy("veiculos.id", "desc")
        ->paginate(1000);

        return response()->json($veiculos);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {

        $validated = $this->validated("store",$request);

        $veiculo = Veiculo::create($validated);

         return response()->json($veiculo);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id):JsonResponse
    {
        $veiculo = Veiculo::find($id);

       return response()->json($veiculo);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
                $id
    ): JsonResponse{

        $veiculo = Veiculo::find($id);
        $validated = $this->validated("update",$request);

        $veiculo->update($validated);

         return response()->json($veiculo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $veiculo = Veiculo::find($id);
        $veiculo->delete();

       return response()->json(["success"=>true,"message"=>"Removed success"]);
    }
    public function find(Request $request)
    {

        if (!empty($request->search)) {
            $rows = Veiculo::search($request->search)->select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
                ->limit(100)
                ->get();
            return response()->json($rows);
        }

        if (!empty($request->id)) {
            $rows = Veiculo::select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
                ->where('id', $request->id)
                ->first();

            return response()->json($rows);
        }

        $rows = Veiculo::select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))

            ->limit(25)
            ->get();
        return response()->json($rows);
    }



}

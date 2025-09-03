<?php

namespace App\Http\Controllers;

use App\Models\Faturamento;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class FaturamentoController extends Controller
{
    public function validated($type,$request){
        if($type=="store"){
        $request->validate(
        [
            'pessoa_motorista_id'=>['nullable'],
            'veiculo_id'=>['nullable'],
            'data_embarque'=>['nullable','boolean'],
            'origem_cidade_id'=>['nullable'],
            'origem_local'=>['nullable','max:65535','string'],
            'destino_cidade_id'=>['nullable'],
            'destino_local'=>['nullable','max:65535','string'],
            'pessoa_cliente_id'=>['nullable'],
            'danfe'=>['nullable'],
            'peso'=>['nullable','numeric'],
            'valor_acerto_motorista'=>['nullable','numeric'],
            'valor_total'=>['nullable','numeric'],
            'DMT'=>['nullable','max:255','string'],
            'carga'=>['nullable','max:65535','string'],
            'descritivo'=>['nullable','max:65535','string'],
        ]
        );
    }else{
        $request->validate([
            'pessoa_motorista_id'=>['nullable'],
            'veiculo_id'=>['nullable'],
            'data_embarque'=>['nullable','boolean'],
            'origem_cidade_id'=>['nullable'],
            'origem_local'=>['nullable','max:65535','string'],
            'destino_cidade_id'=>['nullable'],
            'destino_local'=>['nullable','max:65535','string'],
            'pessoa_cliente_id'=>['nullable'],
            'danfe'=>['nullable'],
            'peso'=>['nullable','numeric'],
            'valor_acerto_motorista'=>['nullable','numeric'],
            'valor_total'=>['nullable','numeric'],
            'DMT'=>['nullable','max:255','string'],
            'carga'=>['nullable','max:65535','string'],
            'descritivo'=>['nullable','max:65535','string'],
        ]);
    }
        return $request->only(["pessoa_motorista_id","veiculo_id","data_embarque","origem_cidade_id","origem_local","destino_cidade_id","destino_local","pessoa_cliente_id","danfe","peso","valor_acerto_motorista","valor_total","DMT","carga","descritivo"]);
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
                DB::raw('concat(origens.nome," (",origens.uf,")") as origem_cidade_id'),
                DB::raw('concat(destinos.nome," (",destinos.uf,")") as destino_cidade_id'),
                DB::raw('concat(clientes.nome," (",clientes.cpf_cnpj,")") as pessoa_cliente_id'),
                DB::raw('REPLACE(faturamentos.valor_total, ".", ",") as valor_total')


            )
            ->join('veiculos', 'faturamentos.veiculo_id', 'veiculos.id')
            ->join('pessoas as motoristas', 'faturamentos.pessoa_motorista_id', 'motoristas.id')
            ->join('cidades as origens', 'faturamentos.origem_cidade_id', 'origens.id')
            ->join('cidades as destinos', 'faturamentos.destino_cidade_id', 'destinos.id')
            ->join('pessoas as clientes', 'faturamentos.pessoa_cliente_id', 'clientes.id')

        ->paginate(1000);

        return response()->json($faturamentos);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {

        $validated = $this->validated("store",$request);

        $faturamento = Faturamento::create($validated);

         return response()->json($faturamento);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id):JsonResponse
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
    ): JsonResponse{

        $faturamento = Faturamento::find($id);
        $validated = $this->validated("update",$request);

        $faturamento->update($validated);

         return response()->json($faturamento);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $faturamento = Faturamento::find($id);
        $faturamento->delete();

       return response()->json(["success"=>true,"message"=>"Removed success"]);
    }

}

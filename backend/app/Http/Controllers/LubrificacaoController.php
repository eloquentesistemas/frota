<?php

namespace App\Http\Controllers;

use App\Models\Lubrificacao;
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
                ]
            );
        } else {
            $request->validate([
                'data' => ['required', 'date'],
                'pessoa_id' => ['required'],
                'veiculo_id' => ['required'],
                'servico' => ['required', 'max:255', 'string'],
                'km' => ['required'],
            ]);
        }
        return $request->only(["data", "pessoa_id", "veiculo_id", "servico", "km"]);
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
                'lubrificacaos.created_at',
                'lubrificacaos.updated_at',
            )
            ->leftJoin('pessoas','lubrificacaos.pessoa_id','pessoas.id')
            ->leftJoin('veiculos','lubrificacaos.veiculo_id','veiculos.id')
            ->paginate(1000);

        return response()->json($lubrificacaos);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {

        $validated = $this->validated("store", $request);

        $lubrificacao = Lubrificacao::create($validated);

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

        $lubrificacao = Lubrificacao::find($id);
        $validated = $this->validated("update", $request);

        $lubrificacao->update($validated);

        return response()->json($lubrificacao);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $lubrificacao = Lubrificacao::find($id);
        $lubrificacao->delete();

        return response()->json(["success" => true, "message" => "Removed success"]);
    }

}

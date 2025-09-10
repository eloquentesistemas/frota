<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class ContaController extends Controller
{
    public function validated($type,$request){
        if($type=="store"){
        $request->validate(
        [
            'data_ocorrido'=>['required','date'],
            'nome'=>['required','max:255','string'],
            'modalidade'=>['required','max:7'],
            'natureza_financeira_id'=>['nullable'],
            'valor'=>['required','numeric'],
            'parcelas'=>['required'],
            'descritivo'=>['nullable','max:65535','string'],
        ]
        );
    }else{
        $request->validate([
            'data_ocorrido'=>['required','date'],
            'nome'=>['required','max:255','string'],
            'modalidade'=>['required','max:7'],
            'natureza_financeira_id'=>['nullable'],
            'valor'=>['required','numeric'],
            'parcelas'=>['required'],
            'descritivo'=>['nullable','max:65535','string'],
        ]);
    }
        return $request->only(["data_ocorrido","nome","modalidade","natureza_financeira_id","valor","parcelas","descritivo"]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->get("search", "");

        $contas = Conta::search($search)
            ->select(
                "contas.id",
                DB::raw("DATE_FORMAT(contas.data_ocorrido, '%d/%m/%Y') as data_ocorrido"),
                "contas.nome",
                "contas.modalidade",
                DB::raw("concat(natureza_financeiras.id,'-',natureza_financeiras.nome) as natureza_financeira_id"),
                "contas.valor",
                "contas.parcelas",
                "contas.descritivo",
                // Soma total pago
                DB::raw("(SELECT COALESCE(SUM(pagamentos.valor),0)
                      FROM pagamentos
                      WHERE pagamentos.conta_id = contas.id) as total_pago"),
                // Status de pagamento
                DB::raw("(CASE
                        WHEN (SELECT COALESCE(SUM(pagamentos.valor),0)
                              FROM pagamentos
                              WHERE pagamentos.conta_id = contas.id) >= contas.valor
                        THEN 'Pago'
                        ELSE 'Não pago'
                     END) as status_pagamento")
            )
            ->leftJoin('natureza_financeiras','contas.natureza_financeira_id','natureza_financeiras.id')
            ->orderBy("contas.id", "desc")
            ->paginate(1000);

        return response()->json($contas);
    }





    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {

        $validated = $this->validated("store",$request);

        $conta = Conta::create($validated);

         return response()->json($conta);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id):JsonResponse
    {
        $conta = Conta::find($id);

       return response()->json($conta);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
                $id
    ): JsonResponse{

        $conta = Conta::find($id);
        $validated = $this->validated("update",$request);

        $conta->update($validated);

         return response()->json($conta);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $conta = Conta::find($id);
        $conta->delete();

       return response()->json(["success"=>true,"message"=>"Removed success"]);
    }

}

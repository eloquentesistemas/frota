<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\Pagamento;
use App\TollBox\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class PagamentoController extends Controller
{
    public function validated($type,$request){
        if($type=="store"){
        $request->validate(
        [
            'data_ocorrido'=>['required','date'],
            'valor'=>['required','numeric'],
            'parcela'=>['required'],
            'descritivo'=>['nullable','max:65535','string'],
            'conta_id'=>['required'],
        ]
        );
    }else{
        $request->validate([
            'data_ocorrido'=>['required','date'],
            'valor'=>['required','numeric'],
            'parcela'=>['required'],
            'descritivo'=>['nullable','max:65535','string'],

        ]);
    }
        return $request->only(["data_ocorrido","valor","parcela","descritivo","conta_id"]);
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

        $pagamentos = Pagamento::search($search)
            ->orderBy("pagamentos.id", "desc")
            ->select(
                'pagamentos.id',
                DB::raw("DATE_FORMAT(pagamentos.data_ocorrido, '%d/%m/%Y') as data_ocorrido"),
                'pagamentos.valor',
                'pagamentos.parcela',
                'pagamentos.descritivo',
                'pagamentos.created_at',
                'pagamentos.updated_at',
                'pagamentos.conta_id'
            )
            ->where('pagamentos.conta_id', $request->conta_id)
            ->paginate(1000);

        return response()->json($pagamentos);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {

        $validated = $this->validated("store",$request);
        $valor = $validated['valor'];
        $conta = Conta::find($request->conta_id);

        if($conta->valor<$valor){
           return  response()->json(['message'=>'Valor acima do esperado de R$'.number_format($conta->valor,2,',',' ')],422);
        }
        $counPagamentos = Pagamento::where('conta_id',$request->conta_id)->count();
        if ($counPagamentos>0) {
            $valorPago = Pagamento::where('conta_id',$request->conta_id)->sum('valor');
            $valorPago = $valorPago+$valor;
            if($conta->valor<$valorPago){
                return response()->json(['message'=>'Valor acima do esperado de R$'.number_format($conta->valor,2,',',' ')],422);
            }
        }
        $parcelaExistente = Pagamento::where('conta_id',$request->conta_id)->where('parcela',$request->parcela)->count();
        if($parcelaExistente>0){
            return response()->json(['message'=>"Parcela $request->parcela cadastrada"],422);
        }

        $parcelasTotais  = Pagamento::where('conta_id',$request->conta_id)->count();

        if($conta->parcelas <= $parcelasTotais){
        return response()->json(['message'=>"Só são permitidas $conta->parcelas parcela(s)"],422);
        }


        $pagamento = Pagamento::create($validated);

         return response()->json($pagamento);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id):JsonResponse
    {
        $pagamento = Pagamento::find($id);

       return response()->json($pagamento);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
                $id
    ): JsonResponse{

        $pagamento = Pagamento::find($id);
        $validated = $this->validated("update",$request);
        $valor = $validated['valor'];
        $conta = Conta::find($pagamento->conta_id);

        if($conta->valor<$valor){
            return  response()->json(['message'=>'Valor acima do esperado de R$'.number_format($conta->valor,2,',',' ')],422);
        }
        $counPagamentos = Pagamento::where('conta_id',$pagamento->conta_id)->count();
        if ($counPagamentos>0) {
            $valorPago = Pagamento::where('conta_id',$pagamento->conta_id)->where('parcela','<>',$pagamento->parcela)->sum('valor');
            $valorPago = $valorPago+$valor;
            if($conta->valor<$valorPago){
                return response()->json(['message'=>'Valor acima do esperado de R$'.number_format($conta->valor,2,',',' ')],422);
            }
        }
        $parcelaExistente = Pagamento::where('conta_id',$pagamento->conta_id)->where('parcela','<>',$pagamento->parcela)->count();
        if($parcelaExistente>1){
            return response()->json(['message'=>"Parcela $request->parcela cadastrada"],422);
        }

        $pagamento->update($validated);

         return response()->json($pagamento);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $pagamento = Pagamento::find($id);
        $pagamento->delete();

       return response()->json(["success"=>true,"message"=>"Removed success"]);
    }

    public function detalhes($conta_id)
    {
       $valorPago =  Pagamento::where('conta_id',$conta_id)->sum('valor');
       $conta = Conta::find($conta_id);
       $valorRestante = $conta->valor - $valorPago;
        $rows =  Pagamento::where('conta_id',$conta_id)->count('id');
        if($valorRestante<=0){
            $status = 'pago';
        }
        if($valorRestante>0){
            $status = 'a pagar';
        }

        $valorPago = number_format($valorPago,2,',',' ');
        $valorRestante = number_format($valorRestante,2,',',' ');
        $valorTotal = number_format($conta->valor,'2',',');

        return response()->json(['valorRestante'=>$valorRestante,'rows'=>$rows ,'valorPago'=>$valorPago,'status'=>$status,'valorTotal'=>$valorTotal]);
    }

}

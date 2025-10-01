<?php

namespace App\Http\Controllers;

use App\Models\Pessoa;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class PessoaController extends Controller
{
    public function validated($type,$request){
        if($type=="store"){
        $request->validate(
        [
            'nome'=>['required','max:255','string'],
            'cpf_cnpj'=>['required','max:17','string'],
            'tipo'=>['required','max:10'],
            'telefone'=>['required','max:20','string'],
            'numero_cnh'=>['nullable','max:11','string'],
            'categoria_cnh'=>['nullable','max:5','string'],
            'vencimento_cnh'=>['nullable','date'],
            'situacao'=>['required','max:7'],
            'cidade_id'=>['nullable'],
            'rua'=>['nullable','max:255','string'],
            'numero'=>['nullable','max:10','string'],
            'descritivo'=>['nullable','max:65535','string'],
        ]
        );
    }else{
        $request->validate([
            'nome'=>['required','max:255','string'],
            'cpf_cnpj'=>['required','max:17','string'],
            'tipo'=>['required','max:10'],
            'telefone'=>['required','max:20','string'],
            'numero_cnh'=>['nullable','max:11','string'],
            'categoria_cnh'=>['nullable','max:5','string'],
            'vencimento_cnh'=>['nullable','date'],
            'situacao'=>['required','max:7'],
            'cidade_id'=>['nullable'],
            'rua'=>['nullable','max:255','string'],
            'numero'=>['nullable','max:10','string'],
            'descritivo'=>['nullable','max:65535','string'],
        ]);
    }
        return $request->only(["nome","cpf_cnpj","tipo","telefone","numero_cnh","categoria_cnh","vencimento_cnh","situacao","cidade_id","rua","numero","descritivo"]);
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
        $pessoas = Pessoa::search($search)
        ->select(
            "pessoas.id",
            "pessoas.nome",
            "pessoas.cpf_cnpj",
            "pessoas.tipo",
            "pessoas.telefone",
            "pessoas.numero_cnh",
            "pessoas.categoria_cnh",
            DB::raw("DATE_FORMAT(pessoas.vencimento_cnh, '%d/%m/%Y') as vencimento_cnh"),
            "situacao",
            DB::raw("CONCAT(cidades.id,'-',cidades.nome,'(',cidades.uf,')') as cidade_id"),

            "pessoas.rua",
            "pessoas.numero",
            "pessoas.descritivo"
        )
            ->leftJoin('cidades','cidades.id','=','pessoas.cidade_id')
            ->orderBy("pessoas.id", "desc")
        ->paginate(1000);

        return response()->json($pessoas);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {

        $validated = $this->validated("store",$request);
        $validated['cpf_cnpj'] = preg_replace('/\D/', '', $validated['cpf_cnpj']);
        $pessoa = Pessoa::create($validated);

         return response()->json($pessoa);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id):JsonResponse
    {
        $pessoa = Pessoa::find($id);

       return response()->json($pessoa);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
                $id
    ): JsonResponse{

        $pessoa = Pessoa::find($id);
        $validated = $this->validated("update",$request);
        $validated['cpf_cnpj'] = preg_replace('/\D/', '', $validated['cpf_cnpj']);
        $pessoa->update($validated);

         return response()->json($pessoa);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $pessoa = Pessoa::find($id);
        $pessoa->delete();

       return response()->json(["success"=>true,"message"=>"Removed success"]);
    }

    public function findMotorista(Request $request)
    {

        if (!empty($request->search)) {
            $rows = Pessoa::search($request->search)->select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
                ->where('tipo','motorista')
                ->where('situacao','ativo')
                ->get();
            return response()->json($rows);
        }

        if (!empty($request->id)) {
            $rows = Pessoa::select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
                ->where('id', $request->id)
                ->first();

            return response()->json($rows);
        }

        $rows = Pessoa::select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
            ->where('tipo','motorista')
            ->where('situacao','ativo')
            ->limit(25)
            ->get();
        return response()->json($rows);
    }
    public function findCliente(Request $request)
    {

        if (!empty($request->search)) {
            $rows = Pessoa::search($request->search)->select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
                ->where('tipo','cliente')
                ->where('situacao','ativo')
                ->get();
            return response()->json($rows);
        }

        if (!empty($request->id)) {
            $rows = Pessoa::select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
                ->where('id', $request->id)
                ->first();

            return response()->json($rows);
        }

        $rows = Pessoa::select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
            ->where('tipo','cliente')
            ->where('situacao','ativo')
            ->limit(25)
            ->get();
        return response()->json($rows);
    }
    public function findFornecedor(Request $request)
    {

        if (!empty($request->search)) {
            $rows = Pessoa::search($request->search)->select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
                ->where('tipo','fornecedor')
                ->where('situacao','ativo')
                ->get();
            return response()->json($rows);
        }

        if (!empty($request->id)) {
            $rows = Pessoa::select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
                ->where('id', $request->id)
                ->first();

            return response()->json($rows);
        }

        $rows = Pessoa::select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
            ->where('tipo','fornecedor')
            ->where('situacao','ativo')
            ->limit(25)
            ->get();
        return response()->json($rows);
    }


}

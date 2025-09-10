<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use App\Models\NaturezaFinanceira;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class NaturezaFinanceiraController extends Controller
{
    public function validated($type,$request){
        if($type=="store"){
        $request->validate(
        [
            'nome'=>['nullable','max:255','string'],
            'descritivo'=>['nullable','max:65535','string'],
        ]
        );
    }else{
        $request->validate([
            'nome'=>['nullable','max:255','string'],
            'descritivo'=>['nullable','max:65535','string'],
        ]);
    }
        return $request->only(["nome","descritivo"]);
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
        $natureza_financeiras = NaturezaFinanceira::search($search)
            ->orderBy("natureza_financeiras.id", "desc")
        ->paginate(1000);

        return response()->json($natureza_financeiras);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {

        $validated = $this->validated("store",$request);

        $natureza_financeira = NaturezaFinanceira::create($validated);

         return response()->json($natureza_financeira);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id):JsonResponse
    {
        $natureza_financeira = NaturezaFinanceira::find($id);

       return response()->json($natureza_financeira);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
                $id
    ): JsonResponse{

        $natureza_financeira = NaturezaFinanceira::find($id);
        $validated = $this->validated("update",$request);

        $natureza_financeira->update($validated);

         return response()->json($natureza_financeira);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $natureza_financeira = NaturezaFinanceira::find($id);
        $natureza_financeira->delete();

       return response()->json(["success"=>true,"message"=>"Removed success"]);
    }

    public function find(Request $request)
    {

        if (!empty($request->search)) {
            $rows = NaturezaFinanceira::search($request->search)->select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
                ->limit(100)
                ->get();
            return response()->json($rows);
        }

        if (!empty($request->id)) {
            $rows = NaturezaFinanceira::select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))
                ->where('id', $request->id)
                ->first();

            return response()->json($rows);
        }

        $rows = NaturezaFinanceira::select('id as code', DB::raw('CONCAT(id,"-",nome) as label'))

            ->limit(25)
            ->get();
        return response()->json($rows);
    }

}

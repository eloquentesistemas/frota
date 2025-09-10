<?php

namespace App\Http\Controllers;

use App\Models\Cidade;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class CidadeController extends Controller
{
    public function validated($type,$request){
        if($type=="store"){
        $request->validate(
        [
            'codigo'=>['required','max:8','string'],
            'nome'=>['required','max:40','string'],
            'uf'=>['required','max:2','string'],
        ]
        );
    }else{
        $request->validate([
            'codigo'=>['required','max:8','string'],
            'nome'=>['required','max:40','string'],
            'uf'=>['required','max:2','string'],
        ]);
    }
        return $request->only(["codigo","nome","uf"]);
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
        $cidades = Cidade::search($search)
            ->orderBy("cidades.id", "desc")
        ->paginate(1000);

        return response()->json($cidades);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {

        $validated = $this->validated("store",$request);

        $cidade = Cidade::create($validated);

         return response()->json($cidade);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id):JsonResponse
    {
        $cidade = Cidade::find($id);

       return response()->json($cidade);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
                $id
    ): JsonResponse{

        $cidade = Cidade::find($id);
        $validated = $this->validated("update",$request);

        $cidade->update($validated);

         return response()->json($cidade);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $cidade = Cidade::find($id);
        $cidade->delete();

       return response()->json(["success"=>true,"message"=>"Removed success"]);
    }
    public function find(Request $request)
    {

        if (!empty($request->search)) {
            $rows = Cidade::search($request->search)->select('id as code', DB::raw('CONCAT(id,"-",nome,"(",uf,")") as label'))
                ->limit(100)
                ->get();
            return response()->json($rows);
        }

        if (!empty($request->id)) {
            $rows = Cidade::select('id as code', DB::raw('CONCAT(id,"-",nome,"(",uf,")") as label'))
                ->where('id', $request->id)
                ->first();

            return response()->json($rows);
        }

        $rows = Cidade::select('id as code', DB::raw('CONCAT(id,"-",nome,"(",uf,")") as label'))

            ->limit(25)
            ->get();
        return response()->json($rows);
    }

}

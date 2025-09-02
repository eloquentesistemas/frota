<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class UserController extends Controller
{
    public function validated($type,$request){
    if($type=="store"){
        $request->validate(
        [
            'name'=>['nullable','max:255','string'],
            'email'=>['nullable','max:255','string'],
            'password'=>['nullable','max:255','string'],
        ]
        );
    }else{
        $request->validate([

            'name'=>['nullable','max:255','string'],
            'email'=>['nullable','max:255','string'],
            'password'=>['nullable','max:255','string']
        ]);
    }
        return $request->only(["name","email","email_verified_at","password","business_id","remember_token",'type']);
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
        $users = User::search($search)
            ->where('business_id', auth()->user()->business_id)
            ->paginate(1000);

        return response()->json($users);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {

        $validated = $this->validated("store",$request);

        $user = User::create($validated);
        $validated['business_id'] = $request->user()->business_id;
         return response()->json($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id):JsonResponse
    {
        $user = User::findOrFail($id);
        $this->authorize("view", $user);

       return response()->json($user);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(
       Request $request,
       $id
    ): JsonResponse{

        $user = User::findOrFail($id);
        $validated = $this->validated("update",$request);

        $user->update($validated);

         return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

       return response()->json(["success"=>true,"message"=>"Removed success"]);
    }


}

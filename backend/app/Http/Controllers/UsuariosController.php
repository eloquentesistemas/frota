<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;


class UsuariosController extends Controller
{
    public function validated($type, $request, $id = null)
    {
        if ($type == "store") {
            $request->validate(
                [
                    'name' => ['required', 'max:255', 'string'],
                    'email' => ['required', 'unique:users', 'max:255', 'string'],
                    'password' => ['required', 'max:255', 'string'],


                ]
            );
        } else {
            $request->validate([
                'name' => ['required', 'max:255', 'string'],
                'email' => ['required', 'max:255', 'string'],
                'password' => ['nullable', 'max:255', 'string'],
            ]);
        }
        return $request->only(["name", "email", "password", "business_id",'type','user_unidade_id','type']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {

        $search = $request->get("search", '');
        $users = User::search($search)

            ->paginate(1000);

        return response()->json($users);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {

        $validated = $this->validated("store", $request);
        $user = User::create($validated);

        return response()->json($user,200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
                $id
    ): JsonResponse
    {

        $user = User::findOrFail($id);

        $validated = $this->validated("update", $request, $id);
        if (User::where('email', $validated['email'])->where('id', '<>', $id)->count() > 0) {
            return response()->json([
                "message" => "The email has already been taken.",
                "errors" => [
                    "email" => [
                        "The email has already been taken."
                    ]
                ]], 422);
        }
        if ($user->email == $validated['email']) {
            unset($validated['email']);
        }
        if(empty($validated['password'])){
            unset($validated['password']);
        }
        $user->update($validated);

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, User $user): JsonResponse
    {

        $user->delete();

        return response()->json(["success" => true, "message" => "Removed success"]);
    }
    public function ver($id): JsonResponse
    {

        $user = User::select('users.name')->findOrFail($id);

        return response()->json($user);
    }

}

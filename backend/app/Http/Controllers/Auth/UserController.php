<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);
        $token = auth()->attempt($credentials);
        if (!$token) {
            abort(401, 'Unauthorized');
        }
        return response()->json(['data' => ['token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60]
        ]);
    }

    public function me(): JsonResponse
    {
        $credentials = auth()->user();
        if(empty($credentials)){
            abort(403,'Unautorized');
        }
        return response()->json($credentials);
    }

    public function register(Request $request): void
    {
        $validated = $request->validate([
            'email' => 'required|email:rfc,dns|unique:users|max:255',
            'name' => 'required|string|max:255',
            'password' => 'required|string|max:255',
        ]);

        $input['name'] = $request->name;
        $input['email'] = $request->email;
        $input['type'] = 'cliente';
        if (!empty($request->password)) {
            $input['password'] = Hash::make($request->password);
        }

        User::create($input);
    }
}

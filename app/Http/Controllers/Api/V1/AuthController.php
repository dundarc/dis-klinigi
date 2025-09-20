<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\Api\V1\UserResource;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('api-token')->plainTextToken;
            return response()->json(['token' => $token, 'user' => new UserResource($user)]);
        }

        return response()->json(['message' => 'Geçersiz kimlik bilgileri'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Başarıyla çıkış yapıldı'], 200);
    }

    public function me(Request $request)
    {
        return new UserResource($request->user());
    }
}
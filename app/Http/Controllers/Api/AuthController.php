<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\SendEmail;
use App\Models\Check;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        Auth::attempt($request->only('email', 'password'));

        if (Auth::check()) {
            $token = Auth::user()->createToken('Token')->plainTextToken;

            return response()->json([
                'success' => true,
                'data' => Auth::user(),
                'token' => $token,
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user',
        ]);

        $token = $user->createToken('Token')->plainTextToken;

        $rand = rand(10000, 99999);

        Check::create([
            'user_id' => $user->id,
            'value' => $rand
        ]);

        SendEmail::dispatch($user->email, $rand);

        return response()->json([
            'success' => true,
            'data' => $user,
            'token' => $token
        ], 200);
    }
    public function logout(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $user->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successfully'
        ], 200);
    }
    public function check(Request $request)
    {
        $user = auth('sanctum')->user();
        if ($request->data != $user->checks->last()->value) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }
        User::where('id', $user->id)->update(['email_verified_at' => now()]);
        return response()->json([
            'success' => true,
            'message' => 'Check successfully'
        ], 200);
    }
}

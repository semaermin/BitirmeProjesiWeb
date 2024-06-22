<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Question;
use App\Models\Test;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class Controller extends BaseController
{
    public function index()
    {
        $user = User::all()->first();
        $testCount = Test::count();
        $questionCount = Question::count();
        $videoCount = Question::where('is_video', true)->count();
        return view('dashboard', compact('user', 'testCount', 'questionCount', 'videoCount'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => $this->passwordRules(),
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json(['message' => 'User registered successfully', 'user' => $user]);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('MyAppToken')->plainTextToken;
            return response()->json(['message' => 'User logged in successfully', 'token' => $token]);
        }

        return response()->json(['message' => 'Unauthorized22'], 401);
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        // Kullanıcı admin değilse, profili döndür
        if ($user->is_admin == 0) {
            return response()->json(['user' => $user]);
        } else {
            return response()->json(['error' => 'Unauthorized', 'message' => 'Only non-admin users can access profile.'], 401);
        }
    }

}

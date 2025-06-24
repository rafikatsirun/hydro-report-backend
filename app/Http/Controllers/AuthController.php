<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'admin', // ⬅️ Setiap user yang registrasi akan menjadi admin
        ]);

        return response()->json(['message' => 'User registered']);
    }


    public function login(Request $request)
    {
        $request->validate(['email' => 'required', 'password' => 'required']);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['email' => ['Invalid credentials']]);
        }

        return response()->json([
            'token' => $user->createToken('hydro-token')->plainTextToken,
            'user' => [
                'name' => $user->name,
                'email' => $user->email,
                'role' => 'admin', // ⬅️ Pastikan role dikirim sebagai admin
            ],
        ]);
    }
}

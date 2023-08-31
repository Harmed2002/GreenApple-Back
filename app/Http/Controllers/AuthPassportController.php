<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthPassportController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth:api', ['except' => ['login', 'register']]);
	}

	public function login(Request $request)
	{
		$credenciales = $request->validate([
			"email" => "required|string|email",
			"password" => "required|string"
		]);

		if (!Auth::attempt($credenciales)) {
			return response()->json(["message" => "No Autenticado"], 401);
		}

		$usuario = Auth::user();

		$token = $usuario->createToken("token personal Passport")->accessToken;

		return response()->json(["access_token" => $token, "user" => $usuario], 200);
	}

	public function register(Request $request)
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|string|email|max:255|unique:users',
			'password' => 'required|string|min:6',
		]);

		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => bcrypt($request->password),
		]);

		return response()->json(['message' => 'User created successfully', 'user' => $user], 201);
	}

	public function logout()
	{
		Auth::user()->tokens->each(function ($token, $key) {
			$token->delete();
		});

		return response()->json('Se ha deslogueado exitosamente', 200);
	}
}

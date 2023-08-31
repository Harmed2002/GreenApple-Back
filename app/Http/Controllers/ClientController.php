<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
	public function index(Request $request)
	{
		$q = isset($request->q) ? $request->q : '';
		if ($q) {
			$clientes = Client::where("fullname", "like", "%$q%")->first();
		} else {
			$clientes = Client::get();
		}
		return response()->json($clientes, 200);
	}

	public function store(Request $request)
	{
		$request->validate([
			"fullname" => "required"
		]);

		$clie = new Client();
		$clie->fullname = $request->fullname;
		$clie->ci_nit = $request->ci_nit;
		$clie->phone = $request->phone;
		$clie->save();

		return response()->json(["message" => "Cliente registrado", "cliente" => $clie], 201);
	}

	public function show(string $id)
	{
		//
	}

	public function update(Request $request, string $id)
	{
		//
	}

	public function destroy(string $id)
	{
		//
	}
}

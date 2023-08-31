<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
	public function index()
	{
        try {
            $pedidos = Order::with(['productos', 'cliente', 'user'])->paginate(10);

            return response()->json($pedidos, 200);

        } catch (\Exception $e) {
            return response()->json(["message" => "Error al obtener los pedidos", "error" => $e->getMessage()], 404);
        }
	}

	public function store(Request $request)
	{
		/* Estructura del pedido
        {
            cliente_id: 3,
            fecha: '2023-08-30 10:45:12',
            observacion: 'Sin observacion',
            productos: [
                {producto_id: 5, cantidad: 2},
                {producto_id: 8, cantidad: 1},
                {producto_id: 11, cantidad: 3},
            ]
        }
        */
		$request->validate([
			"cliente_id" => "required",
			"productos" => "required"
		]);

		// Abro la transacción
		DB::beginTransaction();

		try {
			// Creamos el pedido
			$pedido = new Order();
			$pedido->date = date("Y-m-d H:i:s");
			$pedido->observation = "SIN OBSERVACIONES";
			$pedido->client_id = $request->cliente_id;
			$pedido->user_id = Auth::user()->id;
			$pedido->save();

			// Obtenemos el detalle de productos
			$productos = $request->productos;
			foreach ($productos as $prod) {
				// Obtengo los campos del detalle
				$product_id = $prod["product_id"];
				$quantity = $prod["quantity"];

				// Los asocio al pedido
				$pedido->productos()->attach($product_id, ["quantity" => $quantity]);
			}

			// Cierro la transaccioón si es exitosa
			DB::commit();

            return response()->json(["message" => "Pedido registrado"], 201);

        } catch (\Exception $e) {
			DB::rollback();

            return response()->json(["message" => "Error al registrar el pedido", "error" => $e->getMessage()], 422);
		}
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

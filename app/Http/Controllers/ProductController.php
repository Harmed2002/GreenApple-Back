<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
	public function index()
	{
		// Un ejemplo de ruta es http://127.0.0.1/api/producto?page=&q=tec
		// $buscar = isset($request->q) ? $request->q : '';
        // $limit = isset($request->limit) ? $request->limit : 10;

		// if ($buscar) {
		// 	$productos = Product::orderBy('id', 'desc')
		// 		->where('name', 'like', '%' . $buscar . '%')
		// 		->paginate($limit);
		// } else {
			// $productos = Product::orderBy('id', 'desc')->paginate($limit);
		// }

		$productos = Product::orderBy('id', 'asc')->get();

        // print_r($productos);
        // die();


		return response()->json($productos, 200);
	}

	public function store(Request $request)
	{
		// Validamos los datos
		$request->validate([
			"name" => "required"
		]);

		// Guardamos
		$prod = new Product();
		$prod->name = $request->name;
		$prod->description = $request->description;
		$prod->price = $request->price;
		$prod->save();

		// Respuesta
		return response()->json(["message" => "Producto registrado"], 201);
	}

	public function show(string $id)
	{
		$producto = Product::findOrFail($id); // si no lo encuentra lanza el error 404
		return response()->json([$producto, 200]);
	}

	public function update(Request $request, string $id)
	{
		// Se validan los datos
		$request->validate([
			"name" => "required"
		]);

		// Se busca el producto a modificar
		$prod = Product::findOrFail($id);

		// Guardamos
		$prod->name = $request->name;
		$prod->description = $request->description;
		$prod->price = $request->price;
		$prod->update();

		// Respuesta
		return response()->json(["message" => "Producto actualizado"], 201);
	}

	public function destroy(string $id)
	{
		// Se busca el producto a eliminar
		$prod = Product::findOrFail($id);
		$prod->delete();

		// Respuesta
		return response()->json(["message" => "Producto eliminado"], 200);
	}

    // Función para actualizar la imágen del producto
    public function actualizarImagen(Request $request, $id) {
        if ($file = $request->file("imagen")) {
            $direccion_imagen = time()."-".$file->getClientOriginalName();
            $file->move("imagen/", $direccion_imagen);
            $direccion_imagen = "imagen/".$direccion_imagen;

            $prod = Product::find($id);
            $prod->image = $direccion_imagen;
            $prod->update();

            // Respuesta
		    return response()->json(["message" => "Imagen de producto actualizada"], 200);
        }

        // Respuesta
        return response()->json(["message" => "Se requiere imagen de producto"], 422);
    }
}

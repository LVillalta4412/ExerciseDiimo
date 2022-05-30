<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{
   //Obtengo el listado de todos los productos que estan almacenados en la base de datos.
    public function index()
    {
        return Products::all();
    }

    //Aqui es donde realizo el insert en la base de datos.
    public function store(Request $request)
    {
    //Aqui es donde realizo la validacion antes de almacenar los datos.
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric'
        ]);
        //Me dice si el validador reporta algunos fallos o no se cumple los requisitos
        // entonces me muestra el siguiente JSON
        if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
        }
         //La parte donde envio los datos a la BD deben de ir los valores correctos para que funcione correctamente
        $product = Products::create([
        'sku' => $request->sku,
        'name' => $request->name,
        'quantity' => $request->quantity,
        'price' => $request->price,
        'description' => $request->description,
        'image' => $request->imagen,
       ]);
       //Hago el retorno de los datos almacenados para que se me muestren y
       //verificar que si se almaceno todo de manera correcta
        return $product;
    }

    //Me muestra algún dato en especifico por medio del id.
    public function show($id)
    {
        return Products::where('id',$id)->get();
    }

    //En esta parte actualizo los datos que ya estan almacenados en la BD.
    public function update(Request $request, $id)
    {
        //Aqui es donde realizo la validacion antes de almacenar los datos.
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric',
            'price' => 'required|numeric'
        ]);
        //Me dice si el validador reporta algunos fallos o no se cumple los requisitos
        // entonces me muestra el siguiente JSON
        if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
         }
        $product = Products::findOrFail($id);
        $product -> sku = $request->sku;
        $product -> name = $request->name;
        $product -> quantity = $request->quantity;
        $product -> price = $request->price;
        $product -> description = $request->description;
        $product ->update();
        return $product;

}

//En esta parte actualizo la imagen
public function updateImage(Request $request, $id)
{
    $product = Products::findOrFail($id);
    $product -> image = $request->image;
    $product ->update();
    return $product;

}

    //Aqui es donde procederemos a eliminar los datos por medio del id.
    public function destroy($id)
    {
        $product = Products::findOrFail($id);
        $product->delete();
        return response()->json('User deleted successfully');
    }

    //Aqui es donde está el filtro de busqueda personalizado con like.
    public function search($filter)
    {
            return Products::where('name','LIKE' , '%' . $filter . '%')
            ->orWhere('sku', 'LIKE' , '%' . $filter . '%')->get();

    }
}

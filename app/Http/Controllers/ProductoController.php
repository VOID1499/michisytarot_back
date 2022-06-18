<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Exception;
use stdClass;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Illuminate\Http\Request;

class ProductoController extends Controller
{




    public function listarProductos(Request $request){

        try {

            if ($request['pagina']) {
                $pagina = $request['pagina'];
            } else {
                $pagina = 1;
            }
            if ($request['numFilas']) {
                $numFilas = $request['numFilas'];
            } else {
                $numFilas = 10;
            }
            if ($request['ordenCol']) {
                $ordenCol = $request['ordenCol'];
            } else {
                $ordenCol = 'id';
            }
            if ($request['ordenTipo']) {
                $ordenTipo = $request['ordenTipo'];
            } else {
                $ordenTipo = 'DESC';
            }

            $filterArray = [];

            if ($request['categorias_id']) {
                $filterItem = [];
                array_push($filterItem, 'categorias_id', '=', $request['categorias_id']);
                array_push($filterArray, $filterItem);
            }
            if ($request['nombre']) {
                $filterItem = [];
                array_push($filterItem, 'nombre', 'LIKE', '%' . $request['nombre'] . '%');
                array_push($filterArray, $filterItem);
            }

            if ($request['estado']) {
                $filterItem = [];
                array_push($filterItem, 'estado', '=', $request['estado']);
                array_push($filterArray, $filterItem);
            }

            if($request['paginacion'] == true){
                $productos  =  Producto::where($filterArray)
                ->join('categorias','categorias.id','=','productos.categorias_id')
                ->select('productos.*','categorias.nombre as categoria_nombre')
                ->orderBy($ordenCol, $ordenTipo)
                ->paginate($numFilas, ['*'], 'page',  $pagina);


                $queryJ = json_decode($productos->toJson());

                return response()->json([
                    'code'=>0,
                    'message'=>'Lista de productos paginados',
                    'body' => [
                        'productos' => $queryJ->data,
                        'pagina_actual' => $queryJ->current_page,
                        'total_paginas' => $queryJ->last_page,
                        'total_paginas' => $queryJ->last_page,
                        'numero_filas' => $queryJ->to,
                        'total_registros' => $queryJ->total
                    ]
                ],200);
            }

            if($request['paginacion'] == false){

                $productos = Producto::all();
                return response()->json([
                    'code'=>0,
                    'message'=>'Lista de productos sin paginacion',
                    'body' => [
                        'productos' => $productos
                    ]
                ],200);
            }

        } catch (Exception $e) {
            $error =  $e->getCode();
            $mensajeError = $e->getMessage();
            if ($request->error) {
                $error = $request->error;
            }

            return response()->json([
                'code' => $error,
                'message' => $mensajeError
            ],  500);
        }
       

    }



    public function crearProducto(Request $request){

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:255|unique:productos',
            'descripcion' => 'max:255',
            'categorias_id' => 'required|numeric',
            'estado' => 'numeric|min:0|max:1',
        ]);

        if($validator->fails()){
            return response()->json([
                'code'=> 1,
                'message'=>  $validator->errors(),
            ],400);
        }

        try {
            
         $producto =  Producto::create([
                'nombre'=>$request->nombre,
                'descripcion'=>$request->descripcion,
                'precio'=>$request->precio,
                'estado'=>$request->estado,
                'categorias_id'=>$request->categorias_id,
                'created_at'=>now(),
                'updated_at'=>null
            ]);

            return response()->json([
                "code" => 0,
                "message" => "producto creado",
                "producto" => $producto
            ],200);

        } catch (Exception $e) {
            $error =  $e->getCode();
            $mensajeError = $e->getMessage();
            if ($request->error) {
                $error = $request->error;
            }
    
            return response()->json([
                'code' => $error,
                'message' => $mensajeError
            ],  500);
        }
    }


    public function editarProducto(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'nombre' => [
                'required',
                'max:255',
                Rule::unique('productos')->ignore($request->id,'id')],
            'descripcion' => 'max:255',
            'categorias_id' => 'required|numeric',
            'estado' => 'numeric|min:0|max:1',
        ]);

        if($validator->fails()){
            return response()->json([
                'code'=> 1,
                'message'=>  $validator->errors(),
            ],400);
        }

        try {
            
        $producto =  Producto::find($request->id);
     
        $producto->nombre = $request->nombre;
        $producto->descripcion = $request->descripcion;
        $producto->precio = $request->precio;
        $producto->estado = $request->estado;
        $producto->categorias_id = $request->categorias_id;
        $producto->updated_at = now();

        $producto->save();

            return response()->json([
                "code" => 0,
                "message" => "producto editado",
                "producto" => $producto
            ],200);

        } catch (Exception $e) {
            $error =  $e->getCode();
            $mensajeError = $e->getMessage();
            if ($request->error) {
                $error = $request->error;
            }
    
            return response()->json([
                'code' => $error,
                'message' => $mensajeError
            ],  500);
        }
    }



}

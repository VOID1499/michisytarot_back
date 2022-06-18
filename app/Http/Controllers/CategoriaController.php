<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    


    public function listarCategorias(Request $request){

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
                $categorias  =  Categoria::where($filterArray)
                ->orderBy($ordenCol, $ordenTipo)
                ->paginate($numFilas, ['*'], 'page',  $pagina);


                $queryJ = json_decode($categorias->toJson());

                return response()->json([
                    'code'=>0,
                    'message'=>'Lista de categorias con paginación',
                    'body' => [
                        'categorias' => $queryJ->data,
                        'pagina_actual' => $queryJ->current_page,
                        'total_paginas' => $queryJ->last_page,
                        'total_paginas' => $queryJ->last_page,
                        'numero_filas' => $queryJ->to,
                        'total_registros' => $queryJ->total
                    ]
                ],200);
            }

            if($request['paginacion'] == false){

                $categorias = Categoria::where('estado','=',1);
                return response()->json([
                    'code'=>0,
                    'message'=>'Lista de categorias sin paginacion',
                    'body' => [
                        'categorias' => $categorias
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


    public function crearCategoria(Request $request){

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:255|unique:categorias',
            'estado' => 'numeric|min:0|max:1',
        ]);

        if($validator->fails()){
            return response()->json([
                'code'=> 1,
                'message'=>  $validator->errors(),
            ],400);
        }

        try {
            
         $categoria =  Categoria::create([
                'nombre'=>$request->nombre,
                'estado'=>$request->estado,
                'created_at'=>now(),
                'updated_at'=>null
            ]);

            return response()->json([
                "code" => 0,
                "message" => "categoria creada",
                "categoria" => $categoria
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


    public function editarCategoria(Request $request){

        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
            'nombre' => [
                'required',
                'max:255',
                Rule::unique('categorias')->ignore($request->id,'id')],
            'estado' => 'numeric|min:0|max:1',
        ]);

        if($validator->fails()){
            return response()->json([
                'code'=> 1,
                'message'=>  $validator->errors(),
            ],400);
        }

        try {
            
        $categoria =  Categoria::find($request->id);
     
        $categoria->nombre = $request->nombre;
        $categoria->estado = $request->estado;
        $categoria->updated_at = now();

        $categoria->save();

            return response()->json([
                "code" => 0,
                "message" => "categoria editada",
                "categoria" => $categoria
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

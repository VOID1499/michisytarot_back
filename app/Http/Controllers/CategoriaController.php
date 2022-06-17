<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;

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

            if ($request['id']) {
                $filterItem = [];
                array_push($filterItem, 'id', '=', $request['id']);
                array_push($filterArray, $filterItem);
            }
            if ($request['nombre']) {
                $filterItem = [];
                array_push($filterItem, 'nombre', 'LIKE', '%' . $request['nombre'] . '%');
                array_push($filterArray, $filterItem);
            }


            if($request['paginacion'] == true){
               
            }

            if($request['paginacion'] == false){
              
            }


        } catch (Exception $e) {
         
        }

    }

}

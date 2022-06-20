<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

class ImageController extends Controller
{
     


    public function subirImagen(Request $request){

        $request->validate([
            'image' => 'required|image|max:2048',
            'productos_id' => 'required|numeric'
        ]);

        try {

            //apunta a la carpeta public/storage/public/productos
           $rutaCreada = $request->file('image')->store('public/productos');
           $url = Storage::url($rutaCreada);


           $imagen = Image::create([
                'url' => $url,
                'productos_id' => $request->productos_id,
                'created_at' => now(),
                'updated_at' => null,
           ]);

                return response()->json([
                    "code" => 0,
                    "message" => "imagen",
                    "imagen" => $imagen
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


    public function nose(){
        return 'nose';
        
    }


}

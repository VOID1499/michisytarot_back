<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    

        public function __construct()
        {
        
        }

        public function registrarUsuario(Request $request){

        $validator = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        if($validator->fails()){
            return response()->json([
                'code'=> 1,
                'message'=>  $validator->errors(),
            ],400);
        }

        try{
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
               ]);
        
               $token = $user->createToken('auth_token')->plainTextToken;
        
               return response()->json([
                'code'=> 0,
                'message'=>'Usuario creado',
                'user'=>$user,
                'access_token'=>$token,
                'user'=>$user,
                'token_type' =>'Bearer'
               ],200);

        }catch(Exception $e){
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


    public function login(Request $request){

        if(!Auth::attempt($request->only('email','password'))){

            return response()->json([
                'code' => 0,
                'message' => 'Sin autorización',
            ],401);

        }
        
        $user = User::where('email','=',$request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'code'=> 0,
            'message'=>'Usuario logeado',
            'access_token'=>$token,
            'user'=>$user,
            'token_type' =>'Bearer'
        ]);

    }


    public function logout(){

        //no problemo
       auth()->user()->tokens()->delete();

            return response()->json([
                'code'=> 0,
                'message'=>'Usuario deslogeado, tokens eliminados',
            ]);

    }
}

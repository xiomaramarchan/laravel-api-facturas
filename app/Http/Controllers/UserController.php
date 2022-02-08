<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;



class UserController extends Controller
{
    //INICIO SESION
    public function login(Request $request)
    {   

        $rules = [         
          'email' => 'required|string|email',     
          'password' => 'required|string',     
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(['created' => false, 'error'=>$validator->errors()],422);
        }  

        if (Auth::attempt($request->only('email', 'password'))) {
        $user = Auth::user();
        $token =  $user->createToken('MyApp')->accessToken;
        return response()->json([
          'token' => $token,
          'sub' => $user->id,
          'nit' => $user->nit,
          
        ], 200);
        
        } else {
          return response()->json(['error' => 'Email o password inválidos'], 401);
        }


    }

    //CIERRO SESION
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json(['message' => 'el usuario se ha desconectado'], 200);
    }


    //REGISTRO DE USUARIO
    public function signUp(Request $request)
    {   
         
        date_default_timezone_set('America/Caracas');
       
        $rules = [
          'name' => 'required|string|max:255',
          'email' => 'required|string|email|max:255|unique:users', 
          'nit' => 'required|numeric',   
          'password' => 'required|string|min:8|confirmed',          
        ];

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          return response()->json(['created' => false, 'error'=>$validator->errors()],422);
        }
        
        
        
        $user = new User();
        
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->nit = $request->input('nit');
        $user->password = bcrypt($request->input('password'));     

        $user->save();      
        return response()->json(['message'=>'Usuario registrado'],200);
    }
}

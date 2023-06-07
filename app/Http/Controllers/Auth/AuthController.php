<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Agricultor;
use JWTAuth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function crearCuenta(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [

                'name' => 'required',
                'email' => 'required',
                'password' => 'required',
                'direccion' => 'required',
                'telefono' => 'required',
                'dpi' => 'required',
                'nit' => 'required'
            ],
            [
                //Mensajes a mostrar
                'name.required' => 'Es requerida la informacion de nombre',
                'email.required' => 'Es requerida la informacion de nombre',
                'password.required' => 'Es requerida la informacion de nombre',
                'direccion.required' => 'Es requerida la informacion de direccion',
                'telefono.required' => 'Es requerida la informacion de telefono',
                'dpi.required' => 'Es requerida la informacion de dpi',
                'nit.required' => 'Es requerida la informacion de nit',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                $user = User::create([
                    'name'=> $request->name,
                    'email'=> $request->email,
                    'password'=> Hash::make($request->password),
                    'direccion' => $request->direccion,
                    'telefono' => $request->telefono,
                    'dpi' => $request->dpi,
                    'nit' => $request->nit,
                    'id_estado_agricultor' => '4'
                ]);
                $token = JWTAuth::fromUser($user);

                return response()->json([
                    'user'=>$user,
                    'token'=>$token
                ], 201);
            }

        } catch (Exception $e) {
            return response()->json([
                'mensaje' => 'Error al crear la cuenta'
            ],400);
        }
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
            $credenciales = $request->only('email','password');
            try{
                if(!$token = JWTAuth::attempt($credenciales)){
                    return response()->json([
                        'error' => 'Credenciales Invalidas'
                    ], 400);
                }
            }catch(JWTExcepcion $e){
                return response()->json([
                    'error' => 'Token Invalido'
                ], 500);
            }
            $id_usuario = User::where('email', $request->email)->first();
            $id = $id_usuario->id;
            return response()->json(compact('token','id'));
        }
    }

}

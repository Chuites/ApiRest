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
                'nombre' => 'required',
                'direccion' => 'required',
                'telefono' => 'required',
                'dpi' => 'required',
                'nit' => 'required'
            ],
            [
                //Mensajes a mostrar
                'nombre.required' => 'Es requerida la informacion de nombre',
                'direccion.required' => 'Es requerida la informacion de direccion',
                'telefono.required' => 'Es requerida la informacion de telefono',
                'dpi.required' => 'Es requerida la informacion de dpi',
                'nit.required' => 'Es requerida la informacion de nit',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                $agricultor = new Agricultor();
                $agricultor->nombre = $request->nombre;
                $agricultor->direccion = $request->direccion;
                $agricultor->telefono = $request->telefono;
                $agricultor->dpi = $request->dpi;
                $agricultor->nit = $request->nit;
                if($agricultor->save()){
                    $id_cuenta = Agricultor::where('dpi', $request->dpi)
                    ->where('nit', $request->nit)
                    ->where('nombre', $request->nombre)
                    ->value('id_agricultor');
                    logger($agricultor);
                    $token = JWTAuth::fromUser($agricultor);
                    return response()->json([
                        'mensaje' => 'La cuenta se ha creado correctamente',
                        'id_cuenta' => $id_cuenta,
                        'token' => $token
                    ],200);
                }
            }

        } catch (Exception $e) {
            return response()->json([
                'mensaje' => 'Error al crear la cuenta'
            ],400);
        }
    }

    public function loginAgricultor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required',
            'dpi' => 'required',
            'id_agricultor' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        } else {
            $nombre = $request->nombre;
            $dpi = $request->dpi;
            $id_agricultor = $request->id_agricultor;
            logger('nombre: ',[$nombre]);
            logger('dpi: ',[$dpi]);
            logger('id_agricultor: ',[$id_agricultor]);

            // Buscar al agricultor en la base de datos por nombre y dpi
            $agricultor = Agricultor::where('dpi', $request->dpi)
            ->get()
            ->toArray();
            logger('Agricultor: ',[$agricultor]);

           // $agricultor = new Agricultor();

                try {
                    // Crear un token personalizado para el agricultor autenticado
                    logger('antes de generar token');
                    $token = JWTAuth::fromUser($agricultor);
                    logger('despues de generar token');
                } catch (JWTException $e) {
                    return response()->json([
                        'error' => 'No se pudo crear el token'
                    ], 500);
                }
                return response()->json(compact('token'));
            /* } else {
                return response()->json([
                    'error' => 'Credenciales inválidas'
                ], 400);
            } */
        }
    }

    /* public function register(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors()->all());
        }else{
            $user = User::create([
                'name'=> $request->name,
                'email'=> $request->email,
                'password'=> Hash::make($request->password),
            ]);
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'user'=>$user,
                'token'=>$token
            ], 201);
        }
    } */

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
            return response()->json(compact('token'));
        }
    }

}

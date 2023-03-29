<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use JWTAuth;
use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    public function register(Request $request){
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
            return response()->json(compact('token'));
        }
    }
}

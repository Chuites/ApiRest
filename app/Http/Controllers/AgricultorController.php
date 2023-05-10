<?php

namespace App\Http\Controllers;

use App\Models\Agricultor;
use App\Models\Piloto;
use App\Models\Transporte;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AgricultorController extends Controller
{
    public function testConectividad(Request $request)
    {
        return response()->json([
            'mensaje' => 'Servicio en linea'
        ], 200);
    }

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

                    return response()->json([
                        'mensaje' => 'La cuenta se ha creado correctamente',
                        'id_cuenta' => $id_cuenta
                    ],200);
                }
            }

        } catch (Exception $e) {
            return response()->json([
                'mensaje' => 'Error al crear la cuenta'
            ],400);
        }
    }

    public function confirmarCuenta(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_cuenta' => 'required'
            ],
            [
                //Mensajes a mostrar
                'id_cuenta.required' => 'Es requerida la informacion de id_cuenta'

            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                //buscar id, verficar si existe y si esta activo
                return response()->json([
                    'mensaje' => 'La cuenta se encuentra activa'
                ],200);
            }
        } catch (Exception $e) {
            return response()->json([
                'mensaje' => 'Error al verificar la cuenta'
            ],400);
        }
    }

    public function confirmarPiloto(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'dpi' => 'required'
            ],
            [
                //Mensajes a mostrar
                'dpi.required' => 'Es requerida la informacion del dpi del piloto'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->all());
            }else{
                $piloto = Piloto::where('dpi',$request->dpi)->get();
                $tamaño = count($piloto);
                //Si existe
                if($tamaño != 0){
                    //Si existe y se encuentra activo
                    if($piloto[0]->id_estado_piloto == 24){
                        return response()->json([
                            'mensaje' => 'El piloto se encuentra activo'
                        ],200);
                    //Si existe y NO se encuentra activo
                    }else{
                        return response()->json([
                            'mensaje' => 'El piloto no se encuentra activo'
                        ],200);
                    }
                }else{
                //Si no existe
                    return response()->json([
                        'mensaje' => 'El piloto no se encuentra registrado'
                    ],200);
                }
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'No se ha podido verificar el piloto'], 400);
        }
    }

    public function confirmarTransporte(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'placa' => 'required'
            ],
            [
                //Mensajes a mostrar
                'placa.required' => 'Es requerida la placa del transporte'
            ]);
            if ($validator->fails()) {
                return response()->json($validator->errors()->all(), 201);
            }else{
                $transporte = Transporte::where('placa',$request->placa)->get();
                logger('Placa a validar: ',[$request->placa]);
                $tamaño = count($transporte);
                logger('Tamanio: ',[$tamaño]);
                //Si existe
                if($tamaño != 0){
                    //Si existe y se encuentra activo
                    logger('estado de Tranporte: ', [$transporte[0]->id_estado_transporte]);
                    if($transporte[0]->id_estado_transporte == 4){
                        return response()->json([
                            'mensaje' => 'El transporte se encuentra activo'
                        ],200);
                    //Si existe y NO se encuentra activo
                    }else{
                        return response()->json([
                            'mensaje' => 'El transporte no se encuentra activo'
                        ],200);
                    }
                }else{
                //Si no existe
                    return response()->json([
                        'mensaje' => 'El transporte no existe'
                    ],200);
                }
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'No se ha podido verificar el transporte'], 400);
        }
    }

    public function envioCargamento(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'dpi' => 'required',
                'placa' => 'required',
                'peso' => 'required',
                'parcialidades' => 'required',
                'id_cuenta' => 'required'
            ],
            [
                //Mensajes a mostrar
                'dpi.required' => 'Es requerida el dpi del piloto',
                'placa.required' => 'Es requerida la placa del transporte',
                'peso.required' => 'Es requerido el peso total del cargamento',
                'parcialidades.required' => 'Es requerida las parcilidades',
                'id_cuenta.required' => 'Es requerido el numero de cuenta'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->all(), 400);
            }else{
                //buscar id, verficar si existe y si esta activo
                return response()->json([
                    'mensaje' => 'Se ha registrado el envio del cargamento'
                ],200);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'No se ha podido registrar el envio'], 400);
        }
    }

    public function estadoCargamento(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_cargamento' => 'required',
            ],
            [
                //Mensajes a mostrar
                'id_cargamento.required' => 'Es requerido el numero de cargamento'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->all(), 400);
            }else{
                //buscar id, verficar si existe y si esta activo
                return response()->json([
                    'mensaje' => 'El cargamento ha ingresado a Beneficio'
                ],200);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'No se ha podido encontrar el estado del cargamento'], 400);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agricultores = Agricultor::all();
        return $agricultores;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $agricultor = Agricultor::findOrFail($request->id);

        $agricultor->nombre = $request->nombre;
        $agricultor->direccion = $request->direccion;
        $agricultor->telefono = $request->telefono;
        $agricultor->dpi = $request->dpi;

        $agricultor->save();
        return $agricultor;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $agricultor = Agricultor::destroy($request->id);
        return $agricultor;
    }
}

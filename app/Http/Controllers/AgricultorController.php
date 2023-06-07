<?php

namespace App\Http\Controllers;

use App\Models\Agricultor;
use App\Models\Piloto;
use App\Models\Cargamento;
use App\Models\User;
use App\Models\Transporte;
use App\Models\Parcialidades;
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

    public function infoPesoParcialidad(Request $request)
    {
        $parcialidad = Parcialidades::select('piloto.dpi', 'piloto.nombre_completo', 'estado_piloto.justificacion', 'transporte.placa', 'transporte.marca', 'transporte.color', 'estado_transporte.justificacion as justificacion2', 'parcialidades.id_parcialidad', 'parcialidades.peso', 'parcialidades.peso_certificado')
            ->join('cargamento', 'parcialidades.id_cargamento', '=', 'cargamento.id_cargamento')
            ->join('piloto', 'cargamento.id_piloto', '=', 'piloto.id_piloto')
            ->join('estado_piloto', 'piloto.id_estado_piloto', '=', 'estado_piloto.id_estado_piloto')
            ->join('transporte', 'cargamento.id_transporte', '=', 'transporte.id_transporte')
            ->join('estado_transporte', 'transporte.id_estado_transporte', '=', 'estado_transporte.id_estado_transporte')
            ->where('parcialidades.id_parcialidad', $request->id_parcialidad)
            ->first();

        return response()->json($parcialidad, 200);
    }

    public function infoParcialidad(Request $request)
    {
        $parcialidad = Parcialidades::select('piloto.dpi', 'piloto.nombre_completo', 'estado_piloto.justificacion', 'transporte.placa', 'transporte.marca', 'transporte.color', 'estado_transporte.justificacion as justificacion2')
            ->join('cargamento', 'parcialidades.id_cargamento', '=', 'cargamento.id_cargamento')
            ->join('piloto', 'cargamento.id_piloto', '=', 'piloto.id_piloto')
            ->join('estado_piloto', 'piloto.id_estado_piloto', '=', 'estado_piloto.id_estado_piloto')
            ->join('transporte', 'cargamento.id_transporte', '=', 'transporte.id_transporte')
            ->join('estado_transporte', 'transporte.id_estado_transporte', '=', 'estado_transporte.id_estado_transporte')
            ->where('cargamento.id_cargamento', $request->id_cargamento)
            ->latest('parcialidades.id_parcialidad')
            ->first();

        return response()->json($parcialidad, 200);
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
                logger('TEST');
                $agricultor = new User();
                $agricultor->nombre = $request->nombre;
                $agricultor->direccion = $request->direccion;
                $agricultor->telefono = $request->telefono;
                $agricultor->dpi = $request->dpi;
                $agricultor->nit = $request->nit;
                $agricultor->id_estado_agricultor = 4;
                logger($agricultor);

                if($agricultor->save()){
                    $id_cuenta = User::where('dpi', $request->dpi)
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
                $agricutlor = User::where('id',$request->id_cuenta)->get();
                $tamaño = count($agricutlor);
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
                'dpi_piloto' => 'required',
                'placa_transporte' => 'required',
                'peso_total' => 'required',
                'parcialidades' => 'required',
                'id_cuenta' => 'required'
            ],
            [
                //Mensajes a mostrar
                'dpi_piloto.required' => 'Es requerida el dpi del piloto',
                'placa_transporte.required' => 'Es requerida la placa del transporte',
                'peso_total.required' => 'Es requerido el peso total del cargamento',
                'parcialidades.required' => 'Es requerida las parcilidades',
                'id_cuenta.required' => 'Es requerido el numero de cuenta'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->all(), 400);
            }else{
                logger('DESPUES DE LA VALIDACION');
                $data = array();
                $transporte = Transporte::where('placa',$request->placa_transporte)->get();
                $agricultor = User::where('id',$request->id_cuenta )->get();
                $piloto = Piloto::where('dpi', $request->dpi_piloto)->get();
                $tamaño_transporte = count($transporte);
                $tamaño_agricultor = count($agricultor);
                $tamaño_piloto = count($piloto);
                //Si existe
                if($tamaño_transporte != 0){
                    //Si existe y se encuentra activo
                    if($transporte[0]->id_estado_transporte == 4){
                        //Se almacena el dato para luego enviarlo en la respuesta
                        $id_transporte = $transporte[0]->id_transporte;
                        $data = [
                            'transporte' => 'El transporte se encuentra activo'
                        ];
                        $cargamentos = Cargamento::where('id_transporte', $id_transporte)->exists();
                        if($cargamentos){
                            $valores_estado = [4, 14, 24, 34, 44]; // Array con los valores a evaluar
                            $cargamentos = Cargamento::where('id_transporte', $id_transporte)
                                                    ->whereIn('id_estado_cargamento', $valores_estado)
                                                    ->exists();
                            if($cargamentos){
                                //El transporte aun se encuentra en otro cargamento
                                $data = [
                                    'transporte' => 'El transporte se encuentra en un cargamento que aun esta en proceso'
                                ];
                                return response()->json($data, 200);
                            }
                        }else{

                        }
                    //Si existe y NO se encuentra activo
                    }else{
                        $data = [
                            'transporte' => 'El transporte no se encuentra activo'
                        ];
                    }
                }else{
                    //Si no existe
                    $data = [
                        'transporte' => 'El transporte no existe'
                    ];
                    return response()->json($data, 200);
                }

                if($tamaño_agricultor != 0){
                    //Si existe y se encuentra activo
                    if($agricultor[0]->id_estado_agricultor == 4){
                        //Se almacena el dato para luego enviarlo en la respuesta
                        $data = [
                            'agricultor' => 'El agricultor se encuentra activo'
                        ];
                    //Si existe y NO se encuentra activo
                    }else{
                        $data = [
                            'agricultor' => 'El agricultor no se encuentra activo'
                        ];
                    }
                }else{
                    //Si no existe
                    $data = [
                        'agricultor' => 'El agricultor no existe'
                    ];
                    return response()->json($data, 200);
                }


                if($tamaño_piloto != 0){
                    //Si existe y se encuentra activo
                    if($piloto[0]->id_estado_piloto == 24){
                        //Se almacena el dato para luego enviarlo en la respuesta
                        $id_piloto = $piloto[0]->id_piloto;
                        $data = [
                            'piloto' => 'El piloto se encuentra activo'
                        ];
                    //Si existe y NO se encuentra activo
                    }else{
                        $data = [
                            'piloto' => 'El piloto no se encuentra activo'
                        ];
                    }
                }else{
                    //Si no existe
                    $data = [
                        'piloto' => 'El piloto no existe'
                    ];
                    return response()->json($data, 200);
                }

                //Si todos existen y estan activos
                if (($transporte[0]->id_estado_transporte == 4)&&($agricultor[0]->id_estado_agricultor == 4)&&($piloto[0]->id_estado_piloto == 24)){
                    //Se pocede a guardar los datos del envio
                    logger('CHOMIN!!!');
                    $cargamento = new Cargamento();
                    $cargamento->id_agricultor = $request->id_cuenta;
                    $cargamento->id_transporte = $id_transporte;
                    $cargamento->id_piloto = $id_piloto;
                    $cargamento->peso = $request->peso_total;
                    $cargamento->parcialidades = $request->parcialidades;
                    $cargamento->fh_creacion = date("Y-m-d h:i:s");
                    $cargamento->id_estado_cargamento = 4;
                    $cargamento->save();
                }
                $data = [
                    'piloto' => 'El cargamento se ha creado correctamente'
                ];
                return response()->json($data,200);

            }
        } catch (Exception $e) {
            return response()->json($data, 400);
        }
    }

    public function recibirParcialidad(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_cargamento' => 'required',
            'peso_parcialidad' => 'required'
        ],
        [
            //Mensajes a mostrar
            'id_cargamento.required' => 'Es requerida la informacion de id_cuenta',
            'peso_parcialidad.required' => 'Es requerida la informacion del peso de la parcialidad',
        ]);

        if ($validator->fails()) {
            logger('falla_validacion recibirParcialidad');
            return response()->json($validator->errors()->all());
        }else{
            $parcialidad_existentes = Parcialidades::where('id_cargamento', $request->id_cargamento)->exists();
            $numero_parcialidades = Parcialidades::where('id_cargamento', $request->id_cargamento)->count();
            $parcialidades_permitidas = Cargamento::where('id_cargamento', $request->id_cargamento)->value('parcialidades');
            $peso_total = Cargamento::where('id_cargamento', $request->id_cargamento)->value('peso');

            if ($parcialidad_existentes) {
                $peso_parcialidades = Parcialidades::where('id_cargamento', $request->id_cargamento)->sum('peso');
                //Si no ha cumplido el total de parcialidades
                /* logger('numero de parcialidades en cargamento'.$parcialidades_permitidas);
                logger('previas parcialidades'.$numero_parcialidades); */
                if($parcialidades_permitidas > $numero_parcialidades){
                    //Si el peso se encuentra dentro del rango permitido
                    if(($request->peso_parcialidad + $peso_parcialidades) > $peso_total){
                        $data = [
                            'mensaje' => 'El peso de la parcialidad no puede ser mayor al peso total especificado en el cargamento'
                        ];
                        return response()->json($data, 200);
                    }else{
                        if(($parcialidades_permitidas - 1) == $numero_parcialidades){
                            $peso_requerido = $peso_total - $peso_parcialidades;
                            if($peso_requerido != $request->peso_parcialidad){
                                $data = [
                                    'mensaje' => 'La parcialidad no cumple con el peso total, debe enviar '. $peso_requerido . ' libras para completar este cargamento'
                                ];
                                return response()->json($data, 200);
                            }else{
                                $parcialidad = new Parcialidades();
                                $parcialidad->id_cargamento = $request->id_cargamento;
                                $parcialidad->peso = $request->peso_parcialidad;
                                $parcialidad->id_estado_pesaje = 4;
                                if($parcialidad->save()){
                                    $data = [
                                        'mensaje' => 'Parcialidad registrada correctamente, el cargamento ha sido completado.'
                                    ];
                                    return response()->json($data, 200);
                                }else{
                                    $data = [
                                        'mensaje' => 'Error al registrar parcialidad'
                                    ];
                                    return response()->json($data, 200);
                                }
                            }
                        }else{
                            $parcialidad = new Parcialidades();
                            $parcialidad->id_cargamento = $request->id_cargamento;
                            $parcialidad->peso = $request->peso_parcialidad;
                            $parcialidad->id_estado_pesaje = 4;
                            if($parcialidad->save()){
                                $data = [
                                    'mensaje' => 'Parcialidad registrada correctamente'
                                ];
                                return response()->json($data, 200);
                            }else{
                                $data = [
                                    'mensaje' => 'Error al registrar parcialidad'
                                ];
                                return response()->json($data, 200);
                            }
                        }
                    }
                }else{
                    $data = [
                        'mensaje' => 'El cargamento ya ha sido completado'
                    ];
                    return response()->json($data, 200);
                }
                return response()->json($parcialidad_existentes, 200);
            } else {
                //Cuando no existe aun ninguna parcialidad.
                if($parcialidades_permitidas == 1){
                    if($request->peso_parcialidad == $peso_total){
                        $parcialidad = new Parcialidades();
                        $parcialidad->id_cargamento = $request->id_cargamento;
                        $parcialidad->peso = $request->peso_parcialidad;
                        $parcialidad->id_estado_pesaje = 4;
                        if($parcialidad->save()){

                            $cargamento = Cargamento::where('id_cargamento', $request->id_cargamento)->first();
                            $cargamento->id_estado_cargamento = 14;
                            $cargamento->save();
                            $data = [
                                'mensaje' => 'Parcialidad registrada correctamente'
                            ];
                            return response()->json($data, 200);
                        }else{
                            $data = [
                                'mensaje' => 'Error al registrar parcialidad'
                            ];
                            return response()->json($data, 200);
                        }
                    }else{
                        $data = [
                            'mensaje' => 'El cargamento ya ha sido completado'
                        ];
                        return response()->json($data, 200);
                    }
                }else{
                    if($request->peso_parcialidad > $peso_total){
                        $data = [
                            'mensaje' => 'El peso de la parcialidad no puede ser mayor al peso total del cargamento'
                        ];
                        return response()->json($data, 200);
                    }else{
                        $parcialidad = new Parcialidades();
                        $parcialidad->id_cargamento = $request->id_cargamento;
                        $parcialidad->peso = $request->peso_parcialidad;
                        $parcialidad->id_estado_pesaje = 4;
                        if($parcialidad->save()){
                            $cargamento = Cargamento::where('id_cargamento', $request->id_cargamento)->first();
                            $cargamento->id_estado_cargamento = 14;
                            $cargamento->save();
                            $data = [
                                'mensaje' => 'Parcialidad registrada correctamente'
                            ];
                            return response()->json($data, 200);
                        }else{
                            $data = [
                                'mensaje' => 'Error al registrar parcialidad'
                            ];
                            return response()->json($data, 200);
                        }
                    }
                }
            }

            $resultadoPeso = Cargamento::withSum('parcialidades', 'peso_parcialidad')
                ->select('id_cargamento', 'peso_total', 'numero_parcialidades', 'parcialidades_sum_peso_parcialidad as total_peso_parcialidades')
                ->get();

            $resultadoParcialidades = Cargamento::withCount('parcialidades')
                ->select('id_cargamento', 'peso_total', 'numero_parcialidades', 'parcialidades_count as total_parcialidades')
                ->get();

            logger($resultadoPeso);
            logger($resultadoParcialidades);
            return 0;
            $cargamentos = Cargamento::where('id_agricultor',$request->id_cuenta)->get();
            return response()->json($cargamentos, 200);
        }
    }

    public function certificarPesoParcialidad(Request $request)
    {
        $id_parcialidad = $request->id_parcialidad;
        $peso_certificado = $request->peso_certificado;

        $parcialidad = Parcialidades::where('id_parcialidad', $id_parcialidad)->first();

        if ($parcialidad) {
            $parcialidad->peso_certificado = $request->peso_certificado;
            $parcialidad->id_estado_pesaje = 14;
            logger('Antes del save');
            if ($parcialidad->save()) {
                $id_cargamento = $parcialidad->id_cargamento;
                $cargamento = Cargamento::where('id_cargamento', $id_cargamento)->first();
                $total_parcialidades = $cargamento->parcialidades;
                $parcialidades_pesadas = Parcialidades::where('id_cargamento', $id_cargamento)
                    ->where('id_estado_pesaje', 14)
                    ->count();
                logger('Total de parcialidades: '. $total_parcialidades);
                logger('Parcialidades_Pesadas: '.$parcialidades_pesadas);
                logger('Id de Cargamento: '. $id_cargamento);
                if ($total_parcialidades == $parcialidades_pesadas) {
                    $cargamento = Cargamento::where('id_cargamento', $id_cargamento)->first();
                    $cargamento->id_estado_cargamento = 44;
                    $cargamento->save();
                    logger('Despues de cambiar estado de cargamento');
                }else{
                    $cargamento = Cargamento::where('id_cargamento', $id_cargamento)->first();
                    $cargamento->id_estado_cargamento = 24;
                    $cargamento->save();
                }
                logger('Después del SAVE');
                $parcialidades = Parcialidades::select('parcialidades.id_parcialidad', 'parcialidades.peso', 'transporte.placa', 'transporte.marca', 'transporte.color')
                    ->join('cargamento', 'parcialidades.id_cargamento', '=', 'cargamento.id_cargamento')
                    ->join('transporte', 'cargamento.id_transporte', '=', 'transporte.id_transporte')
                    ->where('parcialidades.id_parcialidad', $request->id_parcialidad)
                    ->get();
                return response()->json($parcialidades, 200);
            }
        }
    }

    public function listadoParcialidades(Request $request)
    {
        $parcialidades = Parcialidades::select('parcialidades.id_parcialidad', 'parcialidades.peso', 'transporte.placa', 'transporte.marca', 'transporte.color')
            ->join('cargamento', 'parcialidades.id_cargamento', '=', 'cargamento.id_cargamento')
            ->join('transporte', 'cargamento.id_transporte', '=', 'transporte.id_transporte')
            ->where('parcialidades.id_estado_pesaje', 4)
            ->get();

        return response()->json($parcialidades, 200);
    }

    public function listadoCargamentos(Request $request)
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
                $cargamentos = Cargamento::where('id_agricultor',$request->id_cuenta)->get();
                return response()->json($cargamentos, 200);
            }
        } catch (Exception $e) {
            return response()->json([
                'mensaje' => 'Error al verificar la cuenta'
            ],400);
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


    public function estadoCargamento(Request $request){
        $cargamento = Cargamento::
        join('estado_cargamento', 'cargamento.id_estado_cargamento', 'estado_cargamento.id_estado_cargamento')
        ->where('id_cargamento', $request->id_cargamento)
        ->first();
        if($cargamento){
            return response()->json($cargamento, 200);
        }else{
            $data = [
                'mensaje' => 'El cargamento no existe'
            ];
            return response()->json($data, 200);
        }
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

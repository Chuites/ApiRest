<?php

namespace App\Http\Controllers;

use App\Models\Agricultor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PesoCabalController extends Controller
{
    public function consultaTransporte(Request $request)
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
                return response()->json($validator->errors()->all(), 400);
            }else{
                //buscar id, verficar si existe y si esta activo
                return response()->json([
                    'mensaje' => 'El tranporte se encuentra activo'
                ],200);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'No se ha podido verificar el transporte'], 400);
        }
    }

    public function certificacionPesaje(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'placa' => 'required',
                'peso_cargamento' => 'required',
                'id_cargamento' => 'required'

            ],
            [
                //Mensajes a mostrar
                'placa.required' => 'Es requerida la placa del transporte',
                'peso_cargamento.required' => 'Es requerido el peso del cargamento',
                'id_cargamento.required' => 'Es requerido el numero de cargamento',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors()->all(), 400);
            }else{
                //buscar id, verficar si existe y si esta activo
                return response()->json([
                    'mensaje' => 'Se ha registrado la certificacion de pesaje'
                ],200);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'No se ha podido certificar el pesaje'], 400);
        }
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

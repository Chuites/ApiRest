<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
/* use App\Models\categoria; */
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\baseModel;
use Session;
use View;
use DB;

class CategoriaController extends Controller
{
    public function getCategoria(){
        return response()->json(categoria::all(), 200);
    }

    public function TestConectividad(){
        return response()->json([
            'mensaje' => 'El servicio se encuentra en linea'
        ],200);
    }

    public function CrearCuenta(Request $request){
        $request->id_coso = 3;

        return response()->json([
            'mensaje' => 'El servicio se encuentra en linea',
            'request' => $request
        ],200);
    }


}

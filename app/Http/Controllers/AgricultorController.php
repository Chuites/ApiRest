<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agricultor;

class AgricultorController extends Controller
{
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
        try {
            $agricultor = new Agricultor();
            $agricultor->nombre = $request->nombre;
            $agricultor->direccion = $request->direccion;
            $agricultor->telefono = $request->telefono;
            $agricultor->dpi = $request->dpi;

            if($agricultor->save()){
                return response()->json([
                    'mensaje' => 'La cuenta se ha creado correctamente'
                ],200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'mensaje' => 'Error al crear la cuenta'
            ],400);
        }
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

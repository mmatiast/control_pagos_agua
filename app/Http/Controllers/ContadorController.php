<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ContadorStoreRequest;
use App\Http\Requests\ContadorUpdateRequest;
use App\Contador;
use DB;

class ContadorController extends Controller
{
    public function index($cliente_id)
    {
    	return response()->json(DB::table('contadores')->where('cliente_id', $cliente_id)->orderBy('id')->get(), 200);
    }

    public function store(ContadorStoreRequest $request, $cliente_id)
    {
        $cliente = Contador::create([
            'ubicacion' => $request->ubicacion,
            'fecha_ultima_lectura' => $request->fecha_ultima_lectura,
            'ultima_lectura' => $request->ultima_lectura,
            'ultimo_mes' => $request->ultimo_mes,
            'ultimo_anio' => $request->ultimo_anio,
            'cliente_id' => $cliente_id,
            'user_id' => $request->user()->id
        ]);
        return response()->json($cliente, 201);
    }

    public function show($id)
    {
    	return response()->json(DB::table('contadores')->where('id', $id)->first(), 200);
    }

    public function update(ContadorUpdateRequest $request, $cliente_id, $id)
    {
    	$contador = Contador::find($id);
        $contador->fill($request->all());
        $contador->save();
        return response()->json($contador, 200);
    }
}

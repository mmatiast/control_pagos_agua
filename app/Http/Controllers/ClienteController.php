<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ClienteStoreRequest;
use App\Http\Requests\ClienteUpdateRequest;
use App\Cliente;
use App\Consumo;
use App\Pago;
use DB;

class ClienteController extends Controller
{
    public function index(Request $request)
    {
    	return response()->json(Cliente::select([
            'id',
            'cui',
            'nombres',
            'apellidos',
            'sexo',
            'estado_civil',
            'celular',
            'correo'
        ])->orderBy('id')->paginate(25), 200);
    }

    public function store(ClienteStoreRequest $request)
    {
        $cliente = Cliente::create([
            'nit' => $request->nit,
            'cui' => $request->cui,
            'nombres' => $request->nombres,
            'apellidos' => $request->apellidos,
            'sexo' => $request->sexo,
            'estado_civil' => $request->estado_civil,
            'celular' => $request->celular,
            'correo' => $request->correo,
            'user_id' => $request->user()->id
        ]);
        return response()->json($cliente, 201);
    }

    public function show($id)
    {
    	return response()->json(Cliente::where('id', $id)->firstOrFail(), 200);
    }

    public function update(ClienteUpdateRequest $request, $id)
    {
    	$cliente = Cliente::find($id);
        $cliente->fill($request->all());
        $cliente->save();
        return response()->json($cliente, 200);
    }

    public function delete($id)
    {
        return response()->json(Cliente::find($id)->delete() ? "El cliente con id $id fue eliminado" : "El cliente con id $id no fue eliminado. Intente más tarde", 200);
    }

    public function restore($id)
    {
        return response()->json(Cliente::onlyTrashed()->find($id)->restore() ? "El cliente con id $id fue restaurado" : "El cliente con id $id no fue restaurado. Intente más tarde", 200);
    }

    public function search(Request $request)
    {
        return response()->json(Cliente::where($request->parametro, $request->operador, $request->operador == 'LIKE' ? "%$request->valor%": $request->valor)->paginate(25),200);
    }

    public function consumos(Request $request, $cliente_id, $contador_id)
    {
        
        /*if($request->anio == 'todos') {
            return response()->json(Consumo::
                where('contador_id', $contador_id)
                ->get(12), 200);
        }*/

        return response()->json(Consumo::
            where('contador_id', $contador_id)
            ->where('anio', $request->anio)
            ->limit(12)
            ->get()
            , 200);
    }

    public function pagos(Request $request, $cliente_id, $contador_id)
    {
        return response()->json(Pago::
            join('consumos', 'pagos.consumo_id', 'consumos.id')
            ->join('forma_pagos', 'pagos.forma_pago_id', 'forma_pagos.id')
            ->join('bancos', 'pagos.banco_id', 'bancos.id')
            ->where('pagos.contador_id', $contador_id)
            ->where('consumos.anio', $request->anio)
            ->select([
                'pagos.id',
                'pagos.fecha_deposito',
                'pagos.numero_deposito',
                'pagos.monto_deposito',
                'pagos.deposito',
                'forma_pagos.forma_pago',
                'bancos.banco',
                'consumos.anio',
                'consumos.mes',
                'consumos.lectura_actual',
                'consumos.ultima_lectura',
            ])
            //->limit(12)
            ->get()
            , 200);
    }
}

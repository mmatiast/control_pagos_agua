<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ReporteController extends Controller
{
    public function morosos()
    {
    	return response()->json(DB::table('consumos')
    		->join('contadores', 'consumos.contador_id', 'contadores.id')
    		->join('clientes', 'contadores.cliente_id', 'clientes.id')
    		->where('estado', 'S')
    		->select([
    			'consumos.id',
    			'clientes.cui',
    			'clientes.nombres',
    			'clientes.apellidos',
    			'clientes.celular',
    			'clientes.correo',
    			'contadores.id AS correlativo',
    			'contadores.ubicacion',
    			'consumos.mes',
    			'consumos.anio',
    			'consumos.monto_consumo',
    			'consumos.monto_exceso',
    			'consumos.fecha_pago',
    			'consumos.estado'
    		])
    		->get()
    		, 200);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class EstadisticaController extends Controller
{
	public function obtenerEstadistica(Request $request, $tabla, $columna, $valor)
	{
		return response()->json(DB::table($tabla)->where($columna, $valor == 'null' ? null : $valor)->count(), 200);
	}

	public function obtenerCantidadDeRegistros($tabla)
	{
		return response()->json(DB::table($tabla)->count(), 200);
	}

	public function morosos()
	{
		return response()->json(DB::table('consumos')
			->where('estado', 'S')
			->count('id')
			, 200);
	}
}

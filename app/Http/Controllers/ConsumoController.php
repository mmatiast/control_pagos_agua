<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ConsumoStoreRequest;
use App\Http\Requests\ConsumoUpdateRequest;
use App\Cliente;
use App\Contador;
use App\Consumo;
use DB;

class ConsumoController extends Controller
{
	public function index($contador_id)
	{
		return response()->json(Consumo::where('contador_id', $contador_id)->where('estado', 'S')->get(), 200);
	}

	public function show($id)
    {
    	return response()->json(Consumo::findOrFail($id), 200);
    }

	public function contadores($contador_id)
    {
    	return response()->json(DB::table('contadores')->where('id', $contador_id)->first(), 200);
    }

	public function store(ConsumoStoreRequest $request, $contador_id)
	{
		DB::beginTransaction();
		try {
			$contador = Contador::find($contador_id);
			$cliente = Cliente::find($contador->cliente_id);
			
			if($request->hasFile('foto_lectura_actual')) {
				$path = $request->file('foto_lectura_actual')->store("public/clientes/consumos/$cliente->cui");
			} else {
				$path = null;
			}

			$consumo = Consumo::create([
				'fecha_emision' => $request->fecha_emision,
				'mes' => $request->mes,
				'anio' => $request->anio,
				'fecha_ultima_lectura' => $contador->fecha_ultima_lectura,
				'ultima_lectura' => $contador->ultima_lectura,
				'fecha_lectura_actual' => $request->fecha_lectura_actual,
				'lectura_actual' => $request->lectura_actual,
				'foto_lectura_actual' => $path,
				'consumo' => $request->consumo,
				'monto_consumo' => $request->monto_consumo,
				'exceso' => $request->exceso == 0 ? null : $request->exceso,
				'monto_exceso' => $request->monto_exceso == 0 ? null : $request->monto_exceso,
				'fecha_pago' => $request->fecha_pago,
				'contador_id' => $contador->id,
				'user_id' => $request->user()->id
			]);

			$contador->fecha_ultima_lectura = $consumo->fecha_lectura_actual;
			$contador->ultima_lectura = $consumo->lectura_actual;
			$contador->ultimo_mes = $consumo->mes;
			$contador->ultimo_anio = $consumo->anio;
			$contador->save();

			DB::commit();

			return response()->json($consumo, 200);
		} catch (\Exception $e) {
			\Storage::delete($path);
			DB::rollback();
			throw $e;
		} catch (\Throwable $e) {
			\Storage::delete($path);
			DB::rollback();
			throw $e;
		}
	}

	public function update(Request $request, $id)
	{
		DB::beginTransaction();
		try {
			$consumo = Consumo::findOrFail($id);
			$contador = Contador::find($consumo->contador_id);
			$cliente = Cliente::find($contador->cliente_id);
			$foto_anterior = $consumo->foto_lectura_actual;
			if($request->hasFile('foto_lectura_actual')) {
				$path = $request->file('foto_lectura_actual')->store("public/clientes/consumos/$cliente->cui");
			} else {
				$path = null;
			}

			$consumo->fill([
				'fecha_emision' => $request->fecha_emision,
				'mes' => $request->mes,
				'anio' => $request->anio,
				'fecha_ultima_lectura' => $request->fecha_ultima_lectura,
				'ultima_lectura' => $request->ultima_lectura,
				'fecha_lectura_actual' => $request->fecha_lectura_actual,
				'lectura_actual' => $request->lectura_actual,
				'foto_lectura_actual' => $path == null ? $foto_anterior : $path,
				'consumo' => $request->consumo,
				'monto_consumo' => $request->monto_consumo,
				'exceso' => $request->exceso == 0 ? null : $request->exceso,
				'monto_exceso' => $request->monto_exceso == 0 ? null : $request->monto_exceso,
				'fecha_pago' => $request->fecha_pago,
				'user_id' => $request->user()->id
			]);

			if($consumo->save()) {
				if($path != null) {
					\Storage::delete($foto_anterior);
				}
			}

			DB::commit();

			return response()->json($consumo, 200);
		} catch (\Exception $e) {
			\Storage::delete($path);
			DB::rollback();
			throw $e;
		} catch (\Throwable $e) {
			\Storage::delete($path);
			DB::rollback();
			throw $e;
		}
	}
}

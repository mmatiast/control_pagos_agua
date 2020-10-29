<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PagoStoreRequest;
use App\Http\Requests\PagoUpdateRequest;
use App\Cliente;
use App\Contador;
use App\Consumo;
use App\Pago;
use DB;

class PagoController extends Controller
{
	public function show($id)
    {
    	return response()->json(Pago::findOrFail($id), 200);
    }

	public function store(PagoStoreRequest $request, $id)
	{
		DB::beginTransaction();
		try {
			$consumo = Consumo::find($id);
			$contador = Contador::find($consumo->contador_id);
			$cliente = Cliente::find($contador->cliente_id);
			if($request->hasFile('deposito')) {
				$path = $request->file('deposito')->store("public/clientes/pagos/$cliente->cui");
			} else {
				$path = null;
			}
			
			$pago = Pago::create([
				'fecha_deposito' => $request->fecha_deposito,
				'numero_deposito' => $request->numero_deposito,
				'monto_deposito' => $request->monto_deposito,
				'deposito' => $path,
				'contador_id' => $request->contador_id,
				'consumo_id' => $id,
				'banco_id' => $request->banco_id,
				'forma_pago_id' => $request->forma_pago_id,
				'user_id' => $request->user()->id
			]);

			$consumo->estado = "P";
			$consumo->save();

			DB::commit();

			return response()->json($pago, 200);
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

	public function update(PagoUpdateRequest $request, $id)
	{
		DB::beginTransaction();
		try {
			$pago = Pago::findOrFail($id);
			$contador = Contador::find($pago->contador_id);
			$cliente = Cliente::find($contador->cliente_id);
			$foto_anterior = $pago->deposito;
			if($request->hasFile('deposito')) {
				$path = $request->file('deposito')->store("public/clientes/pagos/$cliente->cui");
			} else {
				$path = null;
			}
			
			$pago->fill([
				'fecha_deposito' => $request->fecha_deposito,
				'numero_deposito' => $request->numero_deposito,
				'monto_deposito' => $request->monto_deposito,
				'deposito' => $path == null ? $foto_anterior : $path,
				'banco_id' => $request->banco_id,
				'forma_pago_id' => $request->forma_pago_id,
				'user_id' => $request->user()->id
			]);
			
			if($pago->save()) {
				if($path != null) {
					\Storage::delete($foto_anterior);
				}
			}

			DB::commit();

			return response()->json($pago, 200);
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

	public function generarComprobante($cliente_id, $pago_id)
	{
		$cliente = Cliente::findOrFail($cliente_id);
		$pago = Pago::findOrFail($pago_id);
		$contador = Contador::findOrFail($pago->contador_id);
		$consumo = Consumo::findOrFail($pago->consumo_id);
		$meses = [
			'ENERO',
			'FEBRERO',
			'MARZO',
			'ABRIL',
			'MAYO',
			'JUNIO',
			'JULIO',
			'AGOSTO',
			'SEPTIEMBRE',
			'OCTUBRE',
			'NOVIEMBRE',
			'DICIEMBRE'
		];
		return view('comprobante', compact('cliente', 'pago', 'contador', 'consumo', 'meses'));
	}
}

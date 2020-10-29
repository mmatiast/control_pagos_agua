<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FormaPagoStoreRequest;
use App\Http\Requests\FormaPagoUpdateRequest;
use App\FormaPago;
use DB;

class FormaPagoController extends Controller
{
    public function index(Request $request)
    {
        if($request->paginate == 'no'){
            return response()->json(DB::table('forma_pagos')->select('id', 'forma_pago')->orderBy('id')->get(), 200);
        }

    	return response()->json(DB::table('forma_pagos')->select('id', 'forma_pago', 'deleted_at')->orderBy('id')->paginate(5), 200);
    }

    public function store(FormaPagoStoreRequest $request)
    {
        $forma_pago = FormaPago::create([
            'forma_pago' => $request->forma_pago
        ]);
        return response()->json($forma_pago, 201);
    }

    public function show($id)
    {
    	return response()->json(DB::table('forma_pagos')->where('id', $id)->first(), 200);
    }

    public function update(FormaPagoUpdateRequest $request, $id)
    {
    	$forma_pago = FormaPago::find($id);
        $forma_pago->forma_pago = $request->forma_pago;
        $forma_pago->save();
        return response()->json($forma_pago, 200);
    }

    public function delete($id)
    {
        return response()->json(FormaPago::find($id)->delete() ? "La forma de pago con id $id fue eliminada" : "La forma de pago con id $id no fue eliminada. Intente más tarde", 200);
    }

    public function restore($id)
    {
        return response()->json(FormaPago::onlyTrashed()->find($id)->restore() ? "La forma de pago con id $id fue restaurada" : "La forma de pago con id $id no fue restaurada. Intente más tarde", 200);
    }
}

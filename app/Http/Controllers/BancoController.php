<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BancoStoreRequest;
use App\Http\Requests\BancoUpdateRequest;
use App\Banco;
use DB;

class BancoController extends Controller
{
    public function index(Request $request)
    {
        if($request->paginate == 'no'){
            return response()->json(DB::table('bancos')->select('id', 'banco')->orderBy('id')->get(), 200);
        }

    	return response()->json(DB::table('bancos')->select('id', 'banco', 'deleted_at')->orderBy('id')->paginate(5), 200);
    }

    public function store(BancoStoreRequest $request)
    {
        $banco = Banco::create([
            'banco' => $request->banco
        ]);
        return response()->json($banco, 201);
    }

    public function show($id)
    {
    	return response()->json(DB::table('bancos')->where('id', $id)->first(), 200);
    }

    public function update(BancoUpdateRequest $request, $id)
    {
    	$banco = Banco::find($id);
        $banco->banco = $request->banco;
        $banco->save();
        return response()->json($banco, 200);
    }

    public function delete($id)
    {
        return response()->json(Banco::find($id)->delete() ? "El banco con id $id fue eliminado" : "El banco con id $id no fue eliminado. Intente más tarde", 200);
    }

    public function restore($id)
    {
        return response()->json(Banco::onlyTrashed()->find($id)->restore() ? "El banco con id $id fue restaurado" : "El banco con id $id no fue restaurado. Intente más tarde", 200);
    }
}

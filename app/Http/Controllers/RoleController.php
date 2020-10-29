<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RoleStoreRequest;
use App\Http\Requests\RoleUpdateRequest;
use App\Role;
use DB;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if($request->paginate == 'no'){
            return response()->json(DB::table('roles')->select('id', 'role')->orderBy('id')->get(), 200);
        }

        return response()->json(DB::table('roles')->select('id', 'role', 'deleted_at')->orderBy('id')->paginate(5), 200);
    }

    public function store(RoleStoreRequest $request)
    {
        $role = Role::create([
            'role' => $request->role
        ]);
        return response()->json($role, 201);
    }

    public function show($id)
    {
    	return response()->json(DB::table('roles')->where('id', $id)->first(), 200);
    }

    public function update(RoleUpdateRequest $request, $id)
    {
    	$role = Role::find($id);
        $role->role = $request->role;
        $role->save();
        return response()->json($role, 200);
    }

    public function delete($id)
    {
        return response()->json(Role::find($id)->delete() ? "El rol con id $id fue eliminado" : "El rol con id $id no fue eliminado. Intente más tarde", 200);
    }

    public function restore($id)
    {
        return response()->json(Role::onlyTrashed()->find($id)->restore() ? "El rol con id $id fue restaurado" : "El rol con id $id no fue restaurado. Intente más tarde", 200);
    }
}

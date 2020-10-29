<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\User;
use DB;
use Gate;

class UserController extends Controller
{
    public function user(Request $request)
    {
        return $request->user();
    }
    public function index(Request $request)
    {
        return response()->json(DB::table('users')->select([
            'id', 'name', 'email', 'password', 'cui', 'sexo', 'role_id', 'deleted_at'
        ])->orderBy('id', 'DESC')->paginate(4), 200);
    }

    public function store(UserStoreRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'email_verified_at' => now(),
            'password' => \Hash::make($request->password),
            'cui' => $request->cui,
            'sexo' => $request->sexo,
            'role_id' => $request->role_id
        ]);
        return response()->json($user, 201);
    }

    public function show(Request $request, $id)
    {
        if(Gate::forUser($request->user())->allows('show-user', $id)) {
            return response()->json(DB::table('users')->where('id', $id)->first(), 200);
        } else {
            return response()->json("No tiene permisos", 403);
        }
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::find($id);
        $user->fill($request->all());
        $user->save();
        return response()->json($user, 200);
    }

    public function delete($id)
    {
        if($id == 1) {
            return response()->json("Por motivos de seguridad, el usuario root no puede ser eliminado.", 200);
        }
        return response()->json(User::find($id)->delete() ? "El usuario con id $id fue eliminado" : "El usuario con id $id no fue eliminado. Intente más tarde", 200);
    }

    public function restore($id)
    {
        if($id == 1) {
            return response()->json("Por motivos de seguridad, el usuario root no puede ser reestablecido", 200);
        }
        return response()->json(User::onlyTrashed()->find($id)->restore() ? "El usuario con id $id fue restaurado" : "El usuario con id $id no fue restaurado. Intente más tarde", 200);
    }
}

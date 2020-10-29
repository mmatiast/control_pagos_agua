<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = [
    	'nit',
    	'cui',
    	'nombres',
    	'apellidos',
    	'sexo',
    	'estado_civil',
    	'celular',
    	'correo',
        'user_id',
    ];
}

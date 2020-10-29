<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contador extends Model
{
    protected $table = "contadores";

    protected $fillable = [
    	'identificador',
    	'ubicacion',
    	'fecha_ultima_lectura',
    	'ultima_lectura',
    	'ultimo_mes',
    	'ultimo_anio',
    	'cliente_id',
    	'user_id'
    ];
}

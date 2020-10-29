<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Consumo extends Model
{
    protected $fillable = [
    	'fecha_emision',
    	'mes',
    	'anio',
        'fecha_ultima_lectura',
    	'ultima_lectura',
        'fecha_lectura_actual',
    	'lectura_actual',
    	'foto_lectura_actual',
    	'consumo',
    	'monto_consumo',
        'exceso',
    	'monto_exceso',
    	'fecha_pago',
    	'estado',
    	'pdf_recibo',
    	'contador_id',
    	'user_id',
    ];
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recibo extends Model
{
    protected $fillable = [
    	'fecha_emision',
    	'mes',
    	'anio',
    	'ultima_lectura',
    	'lectura_actual',
    	'foto_lectura_actual',
    	'consumo',
    	'monto_consumo',
    	'monto_exceso',
    	'fecha_pago',
    	'estado',
    	'pdf_recibo',
    	'cliente_id',
    	'user_id',
    ];
}

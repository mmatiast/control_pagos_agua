<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
    	'fecha_deposito',
    	'numero_deposito',
    	'monto_deposito',
    	'deposito',
    	'contador_id',
    	'consumo_id',
    	'banco_id',
    	'forma_pago_id',
    	'user_id'
    ];
}

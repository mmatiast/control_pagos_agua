<?php

use Illuminate\Database\Seeder;
use App\FormaPago;

class FormaPagoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        FormaPago::create([
        	'forma_pago' => 'Efectivo'
        ]);

        FormaPago::create([
        	'forma_pago' => 'Tarjeta'
        ]);

        FormaPago::create([
        	'forma_pago' => 'Cheque'
        ]);

        FormaPago::create([
        	'forma_pago' => 'Depósito en efectivo'
        ]);

        FormaPago::create([
        	'forma_pago' => 'Depósito en cheque del mismo banco'
        ]);

        FormaPago::create([
        	'forma_pago' => 'Depósito en cheque de otro banco'
        ]);
    }
}

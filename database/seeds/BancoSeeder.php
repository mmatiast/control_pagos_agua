<?php

use Illuminate\Database\Seeder;
use App\Banco;

class BancoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Banco::create([
        	'banco' => 'Banco G&T Continental, S.A.'
        ]);
    }
}

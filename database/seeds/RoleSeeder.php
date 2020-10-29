<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
        	'role' => 'Administrador'
        ]);

        Role::create([
        	'role' => 'Cajero'
        ]);

        Role::create([
        	'role' => 'Lector'
        ]);

        Role::create([
        	'role' => 'Cliente'
        ]);
    }
}

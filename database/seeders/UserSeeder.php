<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        $admin = User::create([
            'name' => 'Admin Principal',
            'email' => 'admin@crm.com',
            'password' => Hash::make('123456'),
            'role' => 'admin',
            'parent_id' => null
        ]);

        // JEFE
        $jefe = User::create([
            'name' => 'Jefe 1',
            'email' => 'jefe@crm.com',
            'password' => Hash::make('123456'),
            'role' => 'jefe',
            'parent_id' => $admin->id
        ]);

        // SUPERVISOR
        $supervisor = User::create([
            'name' => 'Supervisor 1',
            'email' => 'supervisor@crm.com',
            'password' => Hash::make('123456'),
            'role' => 'supervisor',
            'parent_id' => $jefe->id
        ]);

        // ASESORES
        User::create([
            'name' => 'Asesor 1',
            'email' => 'asesor1@crm.com',
            'password' => Hash::make('123456'),
            'role' => 'asesor',
            'parent_id' => $supervisor->id
        ]);

        User::create([
            'name' => 'Asesor 2',
            'email' => 'asesor2@crm.com',
            'password' => Hash::make('123456'),
            'role' => 'asesor',
            'parent_id' => $supervisor->id
        ]);
    }
}
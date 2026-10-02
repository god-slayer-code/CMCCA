<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $roles = DB::table('roles')->pluck('id', 'nom');

        $users = [
            [
                'name'     => 'Super Admin',
                'email'    => 'superadmin@cmcca.gn',
                'password' => Hash::make('password'),
                'role_id'  => $roles['super_admin'],
            ],
            [
                'name'     => 'Admin',
                'email'    => 'admin@cmcca.gn',
                'password' => Hash::make('password'),
                'role_id'  => $roles['admin'],
            ],
            [
                'name'     => 'Secretaire',
                'email'    => 'secretaire@cmcca.gn',
                'password' => Hash::make('password'),
                'role_id'  => $roles['secretaire'],
            ],
            [
                'name'     => 'Comptable',
                'email'    => 'comptable@cmcca.gn',
                'password' => Hash::make('password'),
                'role_id'  => $roles['comptable'],
            ],
            [
                'name'     => 'Laborantin',
                'email'    => 'laborantin@cmcca.gn',
                'password' => Hash::make('password'),
                'role_id'  => $roles['laborantin'],
            ],
        ];

        foreach ($users as $user) {
            DB::table('users')->insertOrIgnore($user);
        }
    }
}
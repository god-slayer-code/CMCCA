<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {      
        $roles = [
            ['nom' => 'super_admin'],
            ['nom' => 'admin'],
            ['nom' => 'secretaire'],
            ['nom' => 'comptable'],
            ['nom' => 'laborantin'],
        ];
        foreach ($roles as $role) {
            DB::table('roles')->insertOrIgnore($role);
        }
    }
}

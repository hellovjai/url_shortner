<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'password' => Hash::make('admin@123'),
            'role' => 'SuperAdmin',
        ]);

        DB::table('mail_settings')->insert([
            'driver' => 'smtp',
            'host' => 'smtp.gmail.com',
            'port' => '587',
            'from_address' => 'hellovjai@gmail.com',
            'from_name' => 'Vijay Kumar',
            'encryption' => 'tls',
            'username' => 'hellovjai@gmail.com',
            'password' => 'mxcobfhahwyqixqr',
        ]);
    }
}

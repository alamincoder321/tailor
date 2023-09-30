<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create([
            'name' => 'SuperAdmin'
        ]);
        Role::create([
            'name' => 'Admin'
        ]);
        Role::create([
            'name' => 'Tailor'
        ]);

        User::create([
            "name"     => "Super Admin",
            "username" => "admin",
            "role_id"  => 1,
            "email"    => "admin@gmail.com",
            "password" => Hash::make(1)
        ]);
    }
}

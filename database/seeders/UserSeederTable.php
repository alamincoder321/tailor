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
        User::create([
            "name"     => "Admin",
            "username" => "admin",
            "email"    => "admin@gmail.com",
            "password" => Hash::make(1)
        ]);

        Role::create([
            'name' => 'Supper Admin'
        ]);
    }
}

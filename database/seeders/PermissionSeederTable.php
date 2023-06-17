<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                "group_name" => "Dashboard",
                "permission" => [
                    "dashboardView",
                ],
            ],
            [
                "group_name" => "Brand",
                "permission" => [
                    "brandView",
                ],
            ],
        ];

        foreach ($permissions as $permission) {
            $group_name = $permission['group_name'];
            foreach ($permission['permission'] as $perm) {
                Permission::create(["permission" => $perm, "group_name" => $group_name]);
            }
        }
    }
}

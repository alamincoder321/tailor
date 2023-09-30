<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\UserAccess;
use Illuminate\Database\Seeder;

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
                "group_name" => "AccountModule",
                "permission" => [
                    "customerPayment",
                    "tailorPayment",
                    "expenseEntry",
                ],
            ],
            [
                "group_name" => "Brand",
                "permission" => [
                    "brandEntry",
                ],
            ],
            [
                "group_name" => "Category",
                "permission" => [
                    "categoryEntry",
                ],
            ],
            [
                "group_name" => "Clothing",
                "permission" => [
                    "clothingEntry",
                    "clothingList",
                ],
            ],
            [
                "group_name" => "Customer",
                "permission" => [
                    "customerEntry",
                    "customerSMS",
                ],
            ],
            [
                "group_name" => "Designation",
                "permission" => [
                    "designationEntry",
                ],
            ],
            [
                "group_name" => "Employee",
                "permission" => [
                    "employeeEntry",
                    "employeeList",
                ],
            ],
            [
                "group_name" => "Order",
                "permission" => [
                    "orderEntry",
                    "orderList",
                ],
            ],
            [
                "group_name" => "Product",
                "permission" => [
                    "productEntry",
                ],
            ],
            [
                "group_name" => "Report",
                "permission" => [
                    "customerLedger",
                    "customerDueList",
                    "tailorLedger",
                ],
            ],
            [
                "group_name" => "Setting",
                "permission" => [
                    "updateSetting",
                ],
            ],
            [
                "group_name" => "Tailor",
                "permission" => [
                    "tailorEntry",
                ],
            ],
            [
                "group_name" => "User",
                "permission" => [
                    "userEntry",
                    "userAccess",
                    "roleEntry"
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

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'Region Management',
            'District Management',
            'OIC Management',
            'Consumer Management',
            'Employee Management',
            'Own Resource Water Sample Management',
            'Consumer Water Sample Management',
            'Test Result Management',
            'Chemical Inventory Management',
            'Send Email Management',
            'Permission Management',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}

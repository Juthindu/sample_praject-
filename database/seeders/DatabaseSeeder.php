<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            ChemicalSeeder::class,
        ]);

        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'SuperAdmin@example.com',
            'password' => Hash::make('admin123')
        ]);

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);

        $permissions = Permission::all();
        $superAdmin->givePermissionTo($permissions);

        $user->assignRole($superAdmin);
    }
}

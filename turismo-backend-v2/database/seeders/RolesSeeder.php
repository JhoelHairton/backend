<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['superadmin', 'emprendedor', 'turista'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $user = User::firstOrCreate([
            'email' => 'admin@turismo.com',
        ], [
            'name' => 'Super Admin',
            'password' => Hash::make('admin123'),
        ]);

        $user->assignRole('superadmin');
    }
}

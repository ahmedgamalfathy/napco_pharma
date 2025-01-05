<?php

namespace Database\Seeders\Roles;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'all_users',
            'create_user',
            'edit_user',
            'update_user',
            'delete_user',
            'change_user_status',
        ];
        foreach ($permissions as  $permission) {
            Permission::updateOrCreate([
                'name' => $permission,
                'guard_name' => 'api',
            ]);
        }

        $superAdmin = Role::create(['name' => 'superAdmin']);
        $superAdmin->givePermissionTo(Permission::all());
    }
}

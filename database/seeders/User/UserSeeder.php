<?php

namespace Database\Seeders\User;

use App\Models\User;
use App\Enums\User\UserStatus;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'admin dashboard',
            'username'=>"admin",
            'email'=> 'admin@admin.com',
            'address' => 'Mansoura',
            'phone' => '01000000100',
            'status' => UserStatus::ACTIVE->value,
            'password' => Hash::make('ag123456789'),
        ]);
       $role = Role::findByName('superAdmin');
       $user->assignRole($role);

    }
}

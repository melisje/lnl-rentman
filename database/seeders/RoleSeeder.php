<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'admin',
            'invoices',
            'projects',
            'roles',
            'api_tokens',
            'webhookcalls',
            'status',
            'accounts',
            'custom_fields',
        ];

        foreach ($roles as $roleName) {
            Role::updateOrCreate(['name' => $roleName]);
        }
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::findOrCreate('super_admin', 'web');
        Role::findOrCreate('panel_user', 'web');
        Role::findOrCreate('employee', 'web');
    }
}

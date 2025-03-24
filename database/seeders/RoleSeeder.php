<?php

namespace Database\Seeders;

use App\Enums\RoleName;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manager = Role::create(['name' => RoleName::MANAGER, 'guard_name' => 'api']);
        $user = Role::create(['name' => RoleName::USER, 'guard_name' => 'api']);

        $manager->syncPermissions([
            'task.create',
            'task.update',
            'task.view',
            'task.assign',
        ]);

        $user->syncPermissions([
            'task.view',
            'task.status.update',
        ]);
    }
}

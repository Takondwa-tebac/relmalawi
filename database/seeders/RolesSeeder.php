<?php

namespace Database\Seeders;

use App\Support\CmsPermissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (CmsPermissions::all() as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $superAdmin = Role::findOrCreate('super-admin', 'web');
        $superAdmin->syncPermissions(CmsPermissions::all());

        // config/cms.php is the source of truth for the editor's baseline.
        Role::findOrCreate('editor', 'web')->syncPermissions(CmsPermissions::editor());

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}

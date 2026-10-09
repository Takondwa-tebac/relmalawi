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
        $registrar = app(PermissionRegistrar::class);
        $registrar->forgetCachedPermissions();

        $this->createMissingPermissions();

        // Rebuild the permission cache once, after the bulk insert.
        $registrar->forgetCachedPermissions();

        Role::findOrCreate('super-admin', 'web')->syncPermissions(CmsPermissions::all());

        // config/cms.php is the source of truth for the editor's baseline.
        Role::findOrCreate('editor', 'web')->syncPermissions(CmsPermissions::editor());

        $registrar->forgetCachedPermissions();
    }

    /**
     * Insert every permission that does not exist yet in a single query.
     *
     * Permission::findOrCreate() reloads the whole permission cache for each name,
     * which made this seeder take seconds; one bulk insert is equivalent and idempotent.
     */
    private function createMissingPermissions(): void
    {
        $missing = array_diff(
            CmsPermissions::all(),
            Permission::query()->where('guard_name', 'web')->pluck('name')->all(),
        );

        if ($missing === []) {
            return;
        }

        $now = now();

        Permission::query()->insert(array_map(
            fn (string $name) => ['name' => $name, 'guard_name' => 'web', 'created_at' => $now, 'updated_at' => $now],
            array_values($missing),
        ));
    }
}

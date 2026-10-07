<?php

namespace App\Filament\Admin\Resources\Roles\Pages;

use App\Filament\Admin\Resources\Roles\RoleResource;
use App\Filament\Admin\Resources\Roles\Schemas\RoleForm;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordCreation(array $data): Model
    {
        $role = (new Role)->newQuery()->create(['name' => $data['name'], 'guard_name' => 'web']);
        $role->syncPermissions(RoleForm::flatten($data[RoleForm::GROUPS_FIELD] ?? []));

        return $role;
    }
}

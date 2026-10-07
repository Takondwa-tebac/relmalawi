<?php

namespace App\Filament\Admin\Resources\Roles\Schemas;

use App\Support\CmsPermissions;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class RoleForm
{
    public const GROUPS_FIELD = 'permission_groups';

    public static function configure(Schema $schema): Schema
    {
        $sections = [];
        $index = 0;

        foreach (CmsPermissions::grouped() as $label => $options) {
            $sections[] = Section::make($label)
                ->collapsible()
                ->schema([
                    CheckboxList::make(self::GROUPS_FIELD.'.'.$index++)
                        ->hiddenLabel()
                        ->options($options)
                        ->columns(5)
                        ->bulkToggleable(),
                ]);
        }

        return $schema->components([
            Section::make('Role')
                ->columnSpanFull()
                ->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->disabled(fn (?Role $record): bool => $record !== null
                            && in_array($record->name, config('cms.roles'), true)),
                ]),
            Section::make('Permissions')
                ->description('Super-admins always have every permission regardless of what is ticked here.')
                ->columnSpanFull()
                ->schema($sections),
        ]);
    }

    /**
     * Group permission names into the checkbox lists' state.
     *
     * @param  array<int, string>  $granted
     * @return list<list<string>>
     */
    public static function groupState(array $granted): array
    {
        return array_values(array_map(
            fn (array $options): array => array_values(array_intersect(array_keys($options), $granted)),
            CmsPermissions::grouped(),
        ));
    }

    /**
     * Flatten the checkbox lists' state back into permission names.
     *
     * @param  array<int, array<int, string>>  $groups
     * @return list<string>
     */
    public static function flatten(array $groups): array
    {
        return array_values(array_intersect(
            CmsPermissions::all(),
            array_merge([], ...array_map('array_values', $groups)),
        ));
    }
}

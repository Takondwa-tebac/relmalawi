<?php

namespace App\Support;

/**
 * Reads the permission definitions in config/cms.php.
 */
final class CmsPermissions
{
    /**
     * Every permission name.
     *
     * @return list<string>
     */
    public static function all(): array
    {
        $names = [];

        foreach (self::resources() as $key => $resource) {
            foreach ($resource['abilities'] as $ability) {
                $names[] = self::name($ability, $key);
            }
        }

        return [...$names, ...array_keys(self::standalone())];
    }

    /**
     * Permissions granted to the editor role.
     *
     * @return list<string>
     */
    public static function editor(): array
    {
        $names = [];

        foreach (self::resources() as $key => $resource) {
            foreach ($resource['editor'] as $ability) {
                $names[] = self::name($ability, $key);
            }
        }

        return $names;
    }

    public static function name(string $ability, string $resource): string
    {
        return "{$ability} {$resource}";
    }

    /**
     * @return array<string, array{label: string, model: class-string, abilities: list<string>, editor: list<string>}>
     */
    public static function resources(): array
    {
        return config('cms.resources');
    }

    /**
     * @return array<string, string>
     */
    public static function standalone(): array
    {
        return config('cms.permissions');
    }

    /**
     * Grouped options for the role editor: group label => [permission => label].
     *
     * @return array<string, array<string, string>>
     */
    public static function grouped(): array
    {
        $groups = [];

        foreach (self::resources() as $key => $resource) {
            foreach ($resource['abilities'] as $ability) {
                $groups[$resource['label']][self::name($ability, $key)] = ucfirst($ability);
            }
        }

        $groups['Administration'] = self::standalone();

        return $groups;
    }
}

<?php

namespace App\Policies;

use App\Models\User;
use App\Support\CmsPermissions;
use Illuminate\Database\Eloquent\Model;

/**
 * Maps policy methods to the "{ability} {resource}" permissions in config/cms.php.
 */
abstract class ResourcePolicy
{
    /**
     * The permission suffix, i.e. the key in config('cms.resources').
     */
    abstract protected function resource(): string;

    protected function can(User $user, string $ability): bool
    {
        return $user->can(CmsPermissions::name($ability, $this->resource()));
    }

    public function viewAny(User $user): bool
    {
        return $this->can($user, 'view');
    }

    public function view(User $user, Model $model): bool
    {
        return $this->can($user, 'view');
    }

    public function create(User $user): bool
    {
        return $this->can($user, 'create');
    }

    public function update(User $user, Model $model): bool
    {
        return $this->can($user, 'update');
    }

    public function delete(User $user, Model $model): bool
    {
        return $this->can($user, 'delete');
    }

    public function deleteAny(User $user): bool
    {
        return $this->can($user, 'delete');
    }

    public function reorder(User $user): bool
    {
        return $this->can($user, 'reorder');
    }
}

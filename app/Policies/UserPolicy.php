<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class UserPolicy extends ResourcePolicy
{
    protected function resource(): string
    {
        return 'users';
    }

    /**
     * Nobody may delete their own account from the CMS.
     */
    public function delete(User $user, Model $model): bool
    {
        return $user->isNot($model) && $this->can($user, 'delete');
    }
}

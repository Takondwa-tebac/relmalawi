<?php

namespace App\Policies;

class StatPolicy extends ResourcePolicy
{
    protected function resource(): string
    {
        return 'stats';
    }
}

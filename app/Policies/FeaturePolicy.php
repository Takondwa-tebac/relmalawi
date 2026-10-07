<?php

namespace App\Policies;

class FeaturePolicy extends ResourcePolicy
{
    protected function resource(): string
    {
        return 'features';
    }
}

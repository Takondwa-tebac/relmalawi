<?php

namespace App\Policies;

class HowItWorksStepPolicy extends ResourcePolicy
{
    protected function resource(): string
    {
        return 'how-it-works steps';
    }
}

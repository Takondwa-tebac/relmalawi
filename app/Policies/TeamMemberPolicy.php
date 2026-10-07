<?php

namespace App\Policies;

class TeamMemberPolicy extends ResourcePolicy
{
    protected function resource(): string
    {
        return 'team members';
    }
}

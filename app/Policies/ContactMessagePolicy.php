<?php

namespace App\Policies;

class ContactMessagePolicy extends ResourcePolicy
{
    protected function resource(): string
    {
        return 'contact messages';
    }
}

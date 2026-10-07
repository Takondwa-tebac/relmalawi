<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PartnerType: string implements HasLabel
{
    case Radio = 'Radio';
    case Television = 'Television';
    case RadioAndTelevision = 'Radio & Television';

    public function getLabel(): string
    {
        return $this->value;
    }
}

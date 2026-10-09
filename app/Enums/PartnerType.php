<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum PartnerType: string implements HasLabel
{
    case Radio = 'Radio';
    case Television = 'Television';
    case RadioAndTelevision = 'Radio & Television';
    case MobileMoney = 'Mobile money';

    public function getLabel(): string
    {
        return $this->value;
    }
}

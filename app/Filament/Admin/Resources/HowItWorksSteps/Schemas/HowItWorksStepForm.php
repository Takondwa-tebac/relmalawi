<?php

namespace App\Filament\Admin\Resources\HowItWorksSteps\Schemas;

use App\Models\HowItWorksStep;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class HowItWorksStepForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(120),
                Textarea::make('body')
                    ->rows(4)
                    ->maxLength(1000)
                    ->columnSpanFull(),
                Select::make('icon')
                    ->options(HowItWorksStep::ICONS)
                    ->placeholder('No icon'),
                Toggle::make('is_published')
                    ->label('Published')
                    ->default(true),
            ]);
    }
}

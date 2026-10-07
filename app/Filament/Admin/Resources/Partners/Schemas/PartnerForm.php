<?php

namespace App\Filament\Admin\Resources\Partners\Schemas;

use App\Enums\PartnerType;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Partner')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('logo')
                            ->collection('logo')
                            ->image()
                            ->helperText('Optional. Without a logo the site shows an initials tile.')
                            ->columnSpanFull(),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('website_url')
                            ->label('Website')
                            ->url()
                            ->maxLength(255),
                        Radio::make('type')
                            ->options(PartnerType::class)
                            ->required()
                            ->inline()
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(true),
                    ]),
            ]);
    }
}

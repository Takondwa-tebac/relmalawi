<?php

namespace App\Filament\Admin\Resources\TeamMembers\Schemas;

use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Team member')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('photo')
                            ->collection('photo')
                            ->conversion('card')
                            ->image()
                            ->imageEditor()
                            ->imageAspectRatio('4:5')
                            ->columnSpanFull(),
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('role')
                            ->required()
                            ->maxLength(255),
                        Textarea::make('summary')
                            ->label('Headline summary')
                            ->helperText('One sentence shown under the name.')
                            ->rows(2)
                            ->columnSpanFull(),
                        Textarea::make('bio')
                            ->label('Credentials')
                            ->helperText('One credential per line. Each line becomes a bullet on the People page.')
                            ->rows(8)
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

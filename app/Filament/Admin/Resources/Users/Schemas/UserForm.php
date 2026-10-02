<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
               Section::make('User Information')
                ->description('Manage user information and settings.')
                ->columns(4)
                ->columnSpanFull()
                ->schema([
                    SpatieMediaLibraryFileUpload::make('avatar')
                        ->collection('avatars')
                        ->required()
                        ->columnSpanFull(),
                    TextInput::make('name')
                        ->required()
                        ->columnSpan(2),
                    TextInput::make('email')
                        ->label('Email address')
                        ->email()
                        ->required()
                        ->columnSpan(2),
                    DateTimePicker::make('email_verified_at')
                         ->columnSpan(2),
                    TextInput::make('password')
                        ->password()
                        ->required()
                        ->columnSpan(2),
                    Textarea::make('two_factor_secret')
                        ->columnSpan(2),
                    Textarea::make('two_factor_recovery_codes')
                        ->columnSpan(2),
                    DateTimePicker::make('two_factor_confirmed_at')
                        ->columnSpanFull(),
                ])
            ]);
    }
}

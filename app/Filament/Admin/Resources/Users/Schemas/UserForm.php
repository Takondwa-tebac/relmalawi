<?php

namespace App\Filament\Admin\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

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
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->columnSpan(2),
                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->visible(fn (): bool => auth()->user()?->can('manage users') ?? false)
                            // Stops a super-admin from stripping their own super-admin role.
                            ->disabled(fn (?User $record): bool => $record !== null && $record->is(auth()->user()))
                            ->helperText(fn (?User $record): ?string => $record !== null && $record->is(auth()->user())
                                ? 'You cannot change your own roles.'
                                : null)
                            ->columnSpan(2),
                        Textarea::make('two_factor_secret')
                            ->columnSpan(2),
                        Textarea::make('two_factor_recovery_codes')
                            ->columnSpan(2),
                        DateTimePicker::make('two_factor_confirmed_at')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

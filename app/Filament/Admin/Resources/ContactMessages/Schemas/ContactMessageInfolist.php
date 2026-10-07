<?php

namespace App\Filament\Admin\Resources\ContactMessages\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ContactMessageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->copyable(),
                TextEntry::make('created_at')
                    ->label('Received')
                    ->dateTime(),
                TextEntry::make('read_at')
                    ->dateTime()
                    ->placeholder('Unread'),
                TextEntry::make('ip_address')
                    ->label('IP address')
                    ->placeholder('-'),
                TextEntry::make('message')
                    ->columnSpanFull()
                    ->prose()
                    ->extraAttributes(['class' => 'whitespace-pre-line']),
            ]);
    }
}

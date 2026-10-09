<?php

namespace App\Filament\Admin\Resources\Faqs\Schemas;

use App\Models\Faq;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Question')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('category')
                            ->options(array_combine(Faq::CATEGORIES, Faq::CATEGORIES))
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('question')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('answer')
                            ->required()
                            ->rows(6)
                            ->maxLength(3000)
                            ->helperText('Plain text. A blank line starts a new paragraph.')
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('show_on_home')
                            ->label('Show on the home page')
                            ->default(false),
                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(true),
                    ]),
            ]);
    }
}

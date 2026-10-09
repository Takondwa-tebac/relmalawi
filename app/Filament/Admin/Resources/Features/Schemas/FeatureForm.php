<?php

namespace App\Filament\Admin\Resources\Features\Schemas;

use App\Enums\FeatureGroup;
use App\Enums\FeatureIcon;
use App\Models\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FeatureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Placement')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('page_slug')
                            ->label('Page')
                            ->options(array_combine(Page::SLUGS, Page::SLUGS))
                            ->required(),
                        Select::make('group')
                            ->options(FeatureGroup::options())
                            ->required(),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->integer()
                            ->minValue(0)
                            ->default(0)
                            ->required(),
                        Toggle::make('is_published')
                            ->default(true),
                    ]),
                Section::make('Content')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('eyebrow')
                            ->maxLength(255),
                        Select::make('icon')
                            ->options(FeatureIcon::options())
                            ->searchable(),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        Textarea::make('body')
                            ->rows(5)
                            ->helperText('Leave a blank line between paragraphs where a block supports several.')
                            ->columnSpanFull(),
                    ]),
                Section::make('Button')
                    ->description('Only used by call-to-action blocks.')
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        TextInput::make('meta.button_label')
                            ->maxLength(255),
                        TextInput::make('meta.button_url')
                            ->maxLength(255),
                        TextInput::make('meta.secondary_label')
                            ->label('Second button label')
                            ->maxLength(255),
                        TextInput::make('meta.secondary_url')
                            ->label('Second button URL')
                            ->maxLength(255),
                    ]),
                Section::make('Page-specific extras (How it works, Raffles)')
                    ->description('Bullets, short labels and captions used by the richer blocks.')
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsed()
                    ->schema([
                        Textarea::make('meta.bullets')
                            ->label('Bullet points (one per line)')
                            ->rows(4)
                            ->columnSpanFull(),
                        TextInput::make('meta.best_for')
                            ->label('"Best for" line')
                            ->maxLength(255),
                        TextInput::make('meta.caption')
                            ->label('Visual caption')
                            ->maxLength(255),
                    ]),
            ]);
    }
}

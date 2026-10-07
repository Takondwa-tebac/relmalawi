<?php

namespace App\Filament\Admin\Resources\Pages\Schemas;

use App\Models\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Page')
                    ->description('The slug identifies the public page and cannot be changed once created.')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Select::make('slug')
                            ->options(array_combine(Page::SLUGS, Page::SLUGS))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->disabledOn('edit')
                            ->dehydrated(fn (string $operation) => $operation === 'create'),
                        TextInput::make('eyebrow')
                            ->maxLength(255),
                        TextInput::make('title')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('title_accent')
                            ->helperText('Second line of the headline, shown in gold.')
                            ->maxLength(255),
                        Textarea::make('description')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
                Section::make('SEO')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        TextInput::make('meta_title')
                            ->maxLength(255),
                        TextInput::make('meta_description')
                            ->maxLength(255),
                    ]),
            ]);
    }
}

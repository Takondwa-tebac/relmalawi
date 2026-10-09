<?php

namespace App\Filament\Admin\Resources\Pages\Schemas;

use App\Models\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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
                Section::make('Visibility')
                    ->description('Switch a page off to take it down from the website, or keep it live but leave it out of the navbar.')
                    ->columns(2)
                    ->columnSpanFull()
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Page is active')
                            ->helperText(fn (?Page $record) => $record?->slug === 'home'
                                ? 'The home page is the site and cannot be switched off.'
                                : 'When off, visitors get a "page not found" and the page disappears from the navbar and footer. Signed-in staff can still preview it.')
                            ->default(true)
                            ->disabled(fn (?Page $record) => $record?->slug === 'home')
                            ->dehydrated(),
                        Toggle::make('show_in_nav')
                            ->label('Show in the navbar')
                            ->helperText('Home and Contact are not navbar tabs: Home is the logo and Contact is the "Connect" button.')
                            ->default(true),
                        TextInput::make('nav_label')
                            ->label('Navbar label')
                            ->helperText('Leave empty to use the default name.')
                            ->maxLength(40),
                        TextInput::make('nav_sort')
                            ->label('Navbar order')
                            ->helperText('Lower numbers come first. Leave 0 to keep the default order.')
                            ->numeric()
                            ->integer()
                            ->minValue(0)
                            ->maxValue(999)
                            ->default(0),
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

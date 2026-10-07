<?php

namespace App\Filament\Admin\Resources\HowItWorksSteps;

use App\Filament\Admin\Resources\HowItWorksSteps\Pages\CreateHowItWorksStep;
use App\Filament\Admin\Resources\HowItWorksSteps\Pages\EditHowItWorksStep;
use App\Filament\Admin\Resources\HowItWorksSteps\Pages\ListHowItWorksSteps;
use App\Filament\Admin\Resources\HowItWorksSteps\Schemas\HowItWorksStepForm;
use App\Filament\Admin\Resources\HowItWorksSteps\Tables\HowItWorksStepsTable;
use App\Models\HowItWorksStep;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class HowItWorksStepResource extends Resource
{
    protected static ?string $model = HowItWorksStep::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static string|UnitEnum|null $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'How it works steps';

    protected static ?string $modelLabel = 'step';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?int $navigationSort = 20;

    public static function form(Schema $schema): Schema
    {
        return HowItWorksStepForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HowItWorksStepsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHowItWorksSteps::route('/'),
            'create' => CreateHowItWorksStep::route('/create'),
            'edit' => EditHowItWorksStep::route('/{record}/edit'),
        ];
    }
}

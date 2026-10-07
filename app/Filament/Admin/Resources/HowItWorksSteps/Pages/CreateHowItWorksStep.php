<?php

namespace App\Filament\Admin\Resources\HowItWorksSteps\Pages;

use App\Filament\Admin\Resources\HowItWorksSteps\HowItWorksStepResource;
use App\Models\HowItWorksStep;
use Filament\Resources\Pages\CreateRecord;

class CreateHowItWorksStep extends CreateRecord
{
    protected static string $resource = HowItWorksStepResource::class;

    /**
     * New steps go to the end of the list.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['sort_order'] = ((int) HowItWorksStep::query()->max('sort_order')) + 1;

        return $data;
    }
}

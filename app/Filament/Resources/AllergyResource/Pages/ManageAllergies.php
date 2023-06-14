<?php

namespace App\Filament\Resources\AllergyResource\Pages;

use App\Filament\Resources\AllergyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAllergies extends ManageRecords
{
    protected static string $resource = AllergyResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

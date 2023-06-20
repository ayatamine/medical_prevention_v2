<?php

namespace App\Filament\Resources\LabTestResource\Pages;

use App\Filament\Resources\LabTestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLabTests extends ManageRecords
{
    protected static string $resource = LabTestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

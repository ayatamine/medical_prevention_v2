<?php

namespace App\Filament\Resources\MedicalInstructionResource\Pages;

use App\Filament\Resources\MedicalInstructionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMedicalInstructions extends ListRecords
{
    protected static string $resource = MedicalInstructionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\MedicalInstructionResource\Pages;

use App\Filament\Resources\MedicalInstructionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMedicalInstruction extends EditRecord
{
    protected static string $resource = MedicalInstructionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

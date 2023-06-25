<?php

namespace App\Filament\Resources\LabTestResource\Pages;

use App\Filament\Resources\LabTestResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLabTest extends EditRecord
{
    protected static string $resource = LabTestResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

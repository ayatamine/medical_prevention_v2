<?php

namespace App\Filament\Resources\SpecialityResource\Pages;

use App\Filament\Resources\SpecialityResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSpeciality extends EditRecord
{
    protected static string $resource = SpecialityResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

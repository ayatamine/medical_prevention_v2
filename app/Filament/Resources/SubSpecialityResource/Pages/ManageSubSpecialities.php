<?php

namespace App\Filament\Resources\SubSpecialityResource\Pages;

use App\Filament\Resources\SubSpecialityResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSubSpecialities extends ManageRecords
{
    protected static string $resource = SubSpecialityResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

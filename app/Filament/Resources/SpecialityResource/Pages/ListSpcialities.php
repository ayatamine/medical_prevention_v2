<?php

namespace App\Filament\Resources\SpecialityResource\Pages;

use App\Filament\Resources\SpecialityResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSpecialities extends ListRecords
{
    protected static string $resource = SpecialityResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\ChronicDiseasesResource\Pages;

use App\Filament\Resources\ChronicDiseasesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageChronicDiseases extends ManageRecords
{
    protected static string $resource = ChronicDiseasesResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

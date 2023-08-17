<?php

namespace App\Filament\Resources\ChronicDiseaseCategoryResource\Pages;

use App\Filament\Resources\ChronicDiseaseCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChronicDiseaseCategory extends EditRecord
{
    protected static string $resource = ChronicDiseaseCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

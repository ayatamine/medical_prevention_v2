<?php

namespace App\Filament\Resources\ChronicDiseaseCategoryResource\Pages;

use App\Filament\Resources\ChronicDiseaseCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageChronicDiseaseCategories extends ManageRecords
{
    protected static string $resource = ChronicDiseaseCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

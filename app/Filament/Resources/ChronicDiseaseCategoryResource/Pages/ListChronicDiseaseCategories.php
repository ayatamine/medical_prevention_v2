<?php

namespace App\Filament\Resources\ChronicDiseaseCategoryResource\Pages;

use App\Filament\Resources\ChronicDiseaseCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListChronicDiseaseCategories extends ListRecords
{
    protected static string $resource = ChronicDiseaseCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

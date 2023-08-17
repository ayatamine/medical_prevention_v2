<?php

namespace App\Filament\Resources\SymptomeCategoryResource\Pages;

use App\Filament\Resources\SymptomeCategoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSymptomeCategories extends ManageRecords
{
    protected static string $resource = SymptomeCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\FamilyHistoryResource\Pages;

use App\Filament\Resources\FamilyHistoryResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageFamilyHistories extends ManageRecords
{
    protected static string $resource = FamilyHistoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\SymptomeResource\Pages;

use App\Filament\Resources\SymptomeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageSymptomes extends ManageRecords
{
    protected static string $resource = SymptomeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

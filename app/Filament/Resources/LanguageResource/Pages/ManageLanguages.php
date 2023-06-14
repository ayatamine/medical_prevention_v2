<?php

namespace App\Filament\Resources\LanguageResource\Pages;

use App\Filament\Resources\LanguageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageLanguages extends ManageRecords
{
    protected static string $resource = LanguageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

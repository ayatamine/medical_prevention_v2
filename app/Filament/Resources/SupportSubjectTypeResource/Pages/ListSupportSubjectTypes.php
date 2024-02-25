<?php

namespace App\Filament\Resources\SupportSubjectTypeResource\Pages;

use App\Filament\Resources\SupportSubjectTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupportSubjectTypes extends ListRecords
{
    protected static string $resource = SupportSubjectTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

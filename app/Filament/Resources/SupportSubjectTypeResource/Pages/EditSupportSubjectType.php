<?php

namespace App\Filament\Resources\SupportSubjectTypeResource\Pages;

use App\Filament\Resources\SupportSubjectTypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupportSubjectType extends EditRecord
{
    protected static string $resource = SupportSubjectTypeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

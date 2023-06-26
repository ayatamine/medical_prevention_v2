<?php

namespace App\Filament\Resources\ScaleResource\Pages;

use App\Filament\Resources\ScaleResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditScale extends EditRecord
{
    protected static string $resource = ScaleResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

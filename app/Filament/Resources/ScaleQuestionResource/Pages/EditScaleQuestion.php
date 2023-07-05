<?php

namespace App\Filament\Resources\ScaleQuestionResource\Pages;

use App\Filament\Resources\ScaleQuestionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditScaleQuestion extends EditRecord
{
    protected static string $resource = ScaleQuestionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

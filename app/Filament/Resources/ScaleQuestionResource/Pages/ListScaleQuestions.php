<?php

namespace App\Filament\Resources\ScaleQuestionResource\Pages;

use App\Filament\Resources\ScaleQuestionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListScaleQuestions extends ListRecords
{
    protected static string $resource = ScaleQuestionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\RecommendationResource\Pages;

use App\Filament\Resources\RecommendationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRecommendation extends EditRecord
{
    protected static string $resource = RecommendationResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

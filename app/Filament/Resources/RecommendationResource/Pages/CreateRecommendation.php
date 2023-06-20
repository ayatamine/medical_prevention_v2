<?php

namespace App\Filament\Resources\RecommendationResource\Pages;

use App\Filament\Resources\RecommendationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateRecommendation extends CreateRecord
{
    protected static string $resource = RecommendationResource::class;
}

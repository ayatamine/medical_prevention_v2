<?php

namespace App\Filament\Resources\PatientResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\PatientResource;
use App\Filament\Resources\PatientResource\Widgets\PatientOverview;

class ListPatients extends ListRecords
{
    protected static string $resource = PatientResource::class;

    protected function getActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            PatientOverview::class,
        ];
    }
}

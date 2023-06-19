<?php

namespace App\Filament\Resources\DoctorResource\Pages;

use Filament\Pages\Actions;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\DoctorResource;
use App\Filament\Resources\DoctorResource\Widgets\DoctorOverview;

class ListDoctors extends ListRecords
{
    protected static string $resource = DoctorResource::class;
    protected function getHeaderWidgetsColumns(): int | array
    {
        return 3;
    }
    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
    protected function getHeaderWidgets(): array
    {
        return [
            DoctorOverview::class,
        ];
    }
}

<?php

namespace App\Filament\Widgets;

use App\Models\Recommendation;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class Recommendations extends BaseWidget
{
    protected static string $view = 'filament.widgets.recommendations-widget';
    protected int | string | array $columnSpan = [
        'md' => 3,
        'xl' => 2,
    ];
    protected function getCards(): array
    {
        return [
            //
        ];
    }
    protected function getViewData(): array
    {
        $recommendations = Recommendation::publishable()->select('title')->latest('publish_date')->take(8)->get();
        return [
            'recommendations'=>$recommendations
        ];
    }
}

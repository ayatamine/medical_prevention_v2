<?php

namespace App\Filament\Widgets;

use App\Models\Consultation;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class ConsultationOverView extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static string $chartId = 'consultationOverView';
    protected static ?int $sort = 5;
    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Consultations OverView';
    protected int | string | array $columnSpan = 'full';
    
    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $data = Trend::model(Consultation::class)
        ->query(  
            Consultation::query()
            ->completed()
        )
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();
        $incompleted = Trend::model(Consultation::class)
        ->query(  
            Consultation::query()
            ->incompleted()
        )
        ->between(
            start: now()->startOfYear(),
            end: now()->endOfYear(),
        )
        ->perMonth()
        ->count();
        return [
            'chart' => [
                'type' => 'area',
                'height' => 300,
            ],
            'series' => [
                [
                    'name' => 'Completed Consultations',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
                [
                    'name' => 'Incompleted Consultations',
                    'data' => $incompleted->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'xaxis' => [
                'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'colors' => '#9ca3af',
                        'fontWeight' => 600,
                    ],
                ],
            ],
            'colors' => ['#E91E63','#6366f1','#2E93fA', '#66DA26', '#546E7A',  '#FF9800'],

            'stroke' => [
                'curve' => 'smooth',
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
        ];
    }
}

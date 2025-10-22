<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use Filament\Widgets\ChartWidget;

class ApplicationStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Application Status Distribution';

    protected function getData(): array
    {
        $statusCounts = Application::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $labels = array_keys($statusCounts);
        $data = array_values($statusCounts);

        return [
            'datasets' => [
                [
                    'label' => 'Applications',
                    'data' => $data,
                    'backgroundColor' => [
                        '#3B82F6', // Blue
                        '#F59E0B', // Yellow
                        '#10B981', // Green
                        '#EF4444', // Red
                        '#8B5CF6', // Purple
                        '#F97316', // Orange
                    ],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}

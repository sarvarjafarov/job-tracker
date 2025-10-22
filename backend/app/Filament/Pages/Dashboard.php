<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\ApplicationStatsWidget::class,
            \App\Filament\Widgets\ApplicationStatusChart::class,
            \App\Filament\Widgets\RecentApplicationsWidget::class,
        ];
    }
}

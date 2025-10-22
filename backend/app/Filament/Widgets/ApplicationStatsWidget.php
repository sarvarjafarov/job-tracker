<?php

namespace App\Filament\Widgets;

use App\Models\Application;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ApplicationStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $totalApplications = Application::count();
        $appliedCount = Application::where('status', 'applied')->count();
        $interviewedCount = Application::whereIn('status', ['interview_scheduled', 'interviewed', 'technical_interview', 'final_interview'])->count();
        $offersCount = Application::whereIn('status', ['offer_received', 'offer_accepted'])->count();

        return [
            Stat::make('Total Applications', $totalApplications)
                ->description('All time applications')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),
            Stat::make('Applied', $appliedCount)
                ->description('Recently applied')
                ->descriptionIcon('heroicon-m-clock')
                ->color('info'),
            Stat::make('Interviewed', $interviewedCount)
                ->description('In interview process')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('warning'),
            Stat::make('Offers', $offersCount)
                ->description('Received offers')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),
        ];
    }
}

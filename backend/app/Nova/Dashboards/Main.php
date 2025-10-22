<?php

namespace App\Nova\Dashboards;

use Laravel\Nova\Dashboards\Main as Dashboard;
use App\Nova\Metrics\ApplicationStats;
use App\Nova\Metrics\ApplicationStatusBreakdown;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            new ApplicationStats,
            new ApplicationStatusBreakdown,
        ];
    }
}

<?php

namespace App\Nova\Metrics;

use App\Models\Application;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Partition;

class ApplicationStatusBreakdown extends Partition
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Application::class, 'status')
            ->label(function ($value) {
                return match ($value) {
                    'applied' => 'Applied',
                    'under_review' => 'Under Review',
                    'phone_screening' => 'Phone Screening',
                    'interview_scheduled' => 'Interview Scheduled',
                    'interviewed' => 'Interviewed',
                    'technical_interview' => 'Technical Interview',
                    'final_interview' => 'Final Interview',
                    'offer_received' => 'Offer Received',
                    'offer_accepted' => 'Offer Accepted',
                    'offer_declined' => 'Offer Declined',
                    'rejected' => 'Rejected',
                    'withdrawn' => 'Withdrawn',
                    default => ucfirst(str_replace('_', ' ', $value)),
                };
            });
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
        return now()->addMinutes(5);
    }
}

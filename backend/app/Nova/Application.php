<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\URL;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;

class Application extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Application::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'notes', 'source',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('User')
                ->sortable()
                ->searchable(),

            BelongsTo::make('Company')
                ->sortable()
                ->searchable(),

            BelongsTo::make('Job')
                ->nullable()
                ->searchable(),

            Select::make('Status')
                ->options([
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
                ])
                ->displayUsingLabels()
                ->rules('required'),

            Date::make('Applied Date')
                ->sortable()
                ->rules('required'),

            Textarea::make('Notes')
                ->nullable(),

            URL::make('Resume URL')
                ->nullable(),

            URL::make('Cover Letter URL')
                ->nullable(),

            Number::make('Salary Expectation')
                ->nullable()
                ->step(0.01),

            Text::make('Source')
                ->nullable(),

            DateTime::make('Created At')
                ->exceptOnForms(),

            DateTime::make('Updated At')
                ->exceptOnForms(),

            HasMany::make('Interviews'),
            HasMany::make('Application Notes'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}

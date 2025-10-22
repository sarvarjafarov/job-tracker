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
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Http\Requests\NovaRequest;

class Job extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Job::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title', 'description', 'location',
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

            BelongsTo::make('Company')
                ->sortable()
                ->searchable(),

            Text::make('Title')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make('Description')
                ->nullable(),

            Text::make('Location')
                ->nullable(),

            Number::make('Salary Min')
                ->nullable()
                ->step(0.01),

            Number::make('Salary Max')
                ->nullable()
                ->step(0.01),

            Select::make('Employment Type')
                ->options([
                    'full-time' => 'Full Time',
                    'part-time' => 'Part Time',
                    'contract' => 'Contract',
                    'internship' => 'Internship',
                    'freelance' => 'Freelance',
                ])
                ->displayUsingLabels()
                ->rules('required'),

            Select::make('Experience Level')
                ->options([
                    'entry' => 'Entry Level',
                    'mid' => 'Mid Level',
                    'senior' => 'Senior Level',
                    'lead' => 'Lead Level',
                    'executive' => 'Executive Level',
                ])
                ->displayUsingLabels()
                ->rules('required'),

            Boolean::make('Remote Option')
                ->sortable(),

            URL::make('Job URL')
                ->nullable(),

            Date::make('Posted Date')
                ->nullable(),

            Date::make('Application Deadline')
                ->nullable(),

            DateTime::make('Created At')
                ->exceptOnForms(),

            DateTime::make('Updated At')
                ->exceptOnForms(),

            HasMany::make('Applications'),
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

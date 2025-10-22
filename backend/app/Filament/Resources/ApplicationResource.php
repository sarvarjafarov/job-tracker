<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Models\Application;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $navigationGroup = 'Job Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('job_id')
                    ->relationship('job', 'title')
                    ->searchable()
                    ->preload(),
                Forms\Components\Select::make('status')
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
                    ->required(),
                Forms\Components\DatePicker::make('applied_date')
                    ->required(),
                Forms\Components\Textarea::make('notes')
                    ->rows(3),
                Forms\Components\TextInput::make('resume_url')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('cover_letter_url')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('salary_expectation')
                    ->numeric()
                    ->prefix('$'),
                Forms\Components\TextInput::make('source')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('company.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('job.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'applied' => 'info',
                        'under_review' => 'warning',
                        'phone_screening' => 'warning',
                        'interview_scheduled' => 'primary',
                        'interviewed' => 'primary',
                        'technical_interview' => 'primary',
                        'final_interview' => 'primary',
                        'offer_received' => 'success',
                        'offer_accepted' => 'success',
                        'offer_declined' => 'gray',
                        'rejected' => 'danger',
                        'withdrawn' => 'gray',
                    }),
                Tables\Columns\TextColumn::make('applied_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('salary_expectation')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('source')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
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
                    ]),
                Tables\Filters\SelectFilter::make('company')
                    ->relationship('company', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplications::route('/'),
            'create' => Pages\CreateApplication::route('/create'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }
}

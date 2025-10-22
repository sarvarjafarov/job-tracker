<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'interview_date',
        'interview_time',
        'type',
        'location',
        'interviewer_name',
        'interviewer_email',
        'notes',
        'status',
        'feedback',
    ];

    protected $casts = [
        'interview_date' => 'date',
        'interview_time' => 'datetime',
    ];

    /**
     * Get the application that owns the interview
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
}

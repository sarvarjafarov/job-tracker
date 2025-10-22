<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_id',
        'job_id',
        'status',
        'applied_date',
        'notes',
        'resume_url',
        'cover_letter_url',
        'salary_expectation',
        'source',
    ];

    protected $casts = [
        'applied_date' => 'date',
    ];

    /**
     * Get the user that owns the application
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the company for this application
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the job for this application
     */
    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    /**
     * Get interviews for this application
     */
    public function interviews()
    {
        return $this->hasMany(Interview::class);
    }

    /**
     * Get notes for this application
     */
    public function applicationNotes()
    {
        return $this->hasMany(ApplicationNote::class);
    }
}

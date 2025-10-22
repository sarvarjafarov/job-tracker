<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'title',
        'description',
        'location',
        'salary_min',
        'salary_max',
        'employment_type',
        'experience_level',
        'remote_option',
        'job_url',
        'posted_date',
        'application_deadline',
    ];

    protected $casts = [
        'posted_date' => 'date',
        'application_deadline' => 'date',
        'remote_option' => 'boolean',
    ];

    /**
     * Get the company that owns the job
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get applications for this job
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}

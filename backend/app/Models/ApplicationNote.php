<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicationNote extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'user_id',
        'note',
        'is_private',
    ];

    protected $casts = [
        'is_private' => 'boolean',
    ];

    /**
     * Get the application that owns the note
     */
    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Get the user that created the note
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

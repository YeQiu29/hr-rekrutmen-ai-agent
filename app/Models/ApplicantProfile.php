<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Import BelongsTo

class ApplicantProfile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'address',
        'phone_number',
        'education',
        'experience',
        'position_applied',
        'cv_path',
    ];

    /**
     * Get the user that owns the applicant profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
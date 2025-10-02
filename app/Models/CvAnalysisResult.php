<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CvAnalysisResult extends Model
{
    protected $fillable = [
        'user_id',
        'job_vacancy_id',
        'status',
        'result',
    ];

    protected $casts = [
        'result' => 'array',
    ];

    /**
     * Get the user that owns the analysis result.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}

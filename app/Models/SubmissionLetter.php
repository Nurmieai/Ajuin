<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubmissionLetter extends Model
{
    protected $guarded = ['id'];

    protected $fillable = [
        'submission_id',
        'status',
        'letter_number',
        'file_path',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class)->with('user');
    }
}

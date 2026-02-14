<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificates extends Model
{
   protected $fillable = [
        'submission_id',
        'file_path',
        'type'
    ];

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

}

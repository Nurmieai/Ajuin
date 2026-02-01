<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $guarded = ['id'];
    
    public function certificates()
    {
    return $this->hasMany(Certificates::class);
    }

    public function user()
    {
    return $this->belongsTo(User::class);
    }
}

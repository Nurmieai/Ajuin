<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    public function certificates()
    {
    return $this->hasMany(Certificates::class);
    }
}

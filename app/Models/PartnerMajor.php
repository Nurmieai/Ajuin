<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partner extends Model
{
        use SoftDeletes;

        protected $guarded = ['id'];

        protected $fillable = [
                'name',
                'email',
                'phone_number',
                'quota',
                'address',
                'criteria',
                'start_date',
                'finish_date',
        ];
}
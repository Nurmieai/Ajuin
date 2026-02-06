<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
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

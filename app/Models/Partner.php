<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Partner extends Model
{
        use HasFactory;
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

        public function majors(): BelongsToMany
        {
                return $this->belongsToMany(Major::class, 'partner_major');
        }
}

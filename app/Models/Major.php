<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Major extends Model
{
    use HasRoles;
    protected $guarded = ['id'];

    public function partners(): BelongsToMany
    {
        return $this->belongsToMany(Partner::class, 'partner_major');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}

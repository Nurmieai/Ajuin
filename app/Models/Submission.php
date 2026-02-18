<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'partner_id',
        'submission_type',
        'company_name',
        'company_email',
        'company_phone_number',
        'company_address',
        'criteria',
        'start_date',
        'finish_date',
        'status',
        'approved_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'finish_date' => 'date',
        'approved_at' => 'datetime'
    ];

    public function certificates(): HasMany
    {
    return $this->hasMany(Certificates::class);
    }

    public function user(): BelongsTo
    {
    return $this->belongsTo(User::class);
    }

    public function getCertificateByType($type)
    {
        return $this->certificates()->where('type', $type)->first();
    }
}

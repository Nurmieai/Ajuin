<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Submission extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    // helper methods buat gampangin cihuy 
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    public function canBeEdited(): bool
    {
        return $this->isSubmitted() || $this->isRejected();
    }

    public function canBeDeleted(): bool
    {
        return $this->isSubmitted() || $this->isRejected() || $this->isCancelled();
    }

    public function getCertificateByType($type)
    {
        return $this->certificates()->where('type', $type)->first();
    }

    public function userHasApprovedSubmission(): bool
    {
        return self::where('user_id', $this->user_id)
            ->where('status', 'approved')
            ->exists();
    }

    public function getStatusBadgeClass(): string
    {
        return match ($this->status) {
            'submitted' => 'badge-warning',
            'approved' => 'badge-success',
            'rejected' => 'badge-error',
            'cancelled' => 'badge-ghost',
            default => 'badge-neutral'
        };
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'submitted' => 'Menunggu',
            'approved' => 'Diterima',
            'rejected' => 'Ditolak',
            'cancelled' => 'Dibatalkan',
            default => 'Unknown'
        };
    }
    public function getStatusVariant(): string
    {
        return match ($this->status) {
            'submitted' => 'warning',  // Kuning
            'approved'  => 'success',  // Hijau
            'rejected'  => 'danger',   // Merah
            'cancelled' => 'neutral',  // Abu-abu
            default     => 'primary',  // Biru
        };
    }
}

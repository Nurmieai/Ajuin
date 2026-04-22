<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    // spatie
    protected $guard_name = 'web';
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $with = ['roles', 'permissions'];

    protected $fillable = ['username', 'fullname', 'email', 'password', 'nisn', 'major_id', 'is_active','gender','birth_date','nomor_handphone','alamat_tinggal', 'cv_url', 'portfolio_url'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean'
        ];
    }
// Relasi model
    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

 // Helper - Status Check
    public function isActive(): bool
    {
        return $this->is_active === true;
    }

    public function isInactive(): bool
    {
        return $this->is_active === false;
    }

    public function isArchived(): bool
    {
        return $this->trashed();
    }

    // Helper - Statsitik PKL
    public function getTotalSubmissions(): int
    {
        return $this->submissions()->count();
    }

    public function getApprovedSubmissions()
    {
        return $this->submissions()->where('status', 'approved')->get();
    }

    public function hasApprovedSubmission(): bool
    {
        return $this->submissions()->where('status', 'approved')->exists();
    }

    // Scope Queries
    public function scopeStudents($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('name', 'student');
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }
}

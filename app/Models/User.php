<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'contact_email',
        'date_of_birth',
        'email',
        'membership_id',
        'membership_type',
        'name',
        'password',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return Attribute
     */
    public function contactEmail(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ?: $this->email,
            set: fn (?string $value) => $value !== $this->email ?: ''
        );
    }

    /**
     * @return BelongsToMany
     */
    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class);
    }

    /**
     * @return HasMany
     */
    public function identifications(): HasMany
    {
        return $this->hasMany(Identification::class);
    }

    /**
     * @return BelongsToMany
     */
    public function membership(): BelongsToMany
    {
        return $this->belongsToMany(Membership::class)->withPivot('relationship', 'type');
    }

    /**
     * @return HasMany
     */
    public function membership_user(): HasMany
    {
        return $this->hasMany(MembershipUser::class);
    }

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
        ];
    }
}

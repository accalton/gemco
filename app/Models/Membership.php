<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Membership extends Model
{
    use HasFactory;

    public const string STATUS_ACTIVE = 'active';
    public const string STATUS_CANCELLED = 'cancelled';
    public const string STATUS_EXPIRED = 'expired';
    public const string STATUS_PENDING = 'pending';
    public const string STATUS_REVOKED = 'revoked';

    public const array STATUSES = [
        self::STATUS_ACTIVE    => 'Active',
        self::STATUS_CANCELLED => 'Cancelled',
        self::STATUS_PENDING   => 'Pending',
        self::STATUS_REVOKED   => 'Revoked',
    ];

    public const string TYPE_ADULT = 'adult';
    public const string TYPE_FAMILY = 'family';
    public const string TYPE_YOUTH = 'youth';

    public const array TYPES = [
        self::TYPE_ADULT       => 'Adult',
        self::TYPE_FAMILY      => 'Family',
        self::TYPE_YOUTH       => 'Youth',
    ];

    protected $fillable = [
        'expiry',
        'member_id',
        'status',
        'type',
    ];

    /**
     * @return BelongsTo
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * @return BelongsToMany
     */
    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(Member::class)
            ->as('memberMembership')
            ->wherePivot('type', 'contact')
            ->withPivot('relationship', 'type')
            ->orderByPivot('order');
    }

    /**
     * @return BelongsTo
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * @return BelongsToMany
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(Member::class)
            ->as('memberMembership')
            ->wherePivot('type', 'member')
            ->withPivot('relationship', 'type')
            ->orderByPivot('order');
    }

    /**
     * @return HasMany
     */
    public function member_memberships(): HasMany
    {
        return $this->hasMany(MemberMembership::class);
    }
}

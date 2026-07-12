<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    public const string TYPE_ASSOCIATE = 'associate';
    public const string TYPE_CONCESSION = 'concession';
    public const string TYPE_FAMILY = 'family';
    public const string TYPE_LIFE = 'life';
    public const string TYPE_YOUTH = 'youth';

    public const array TYPES = [
        self::TYPE_ADULT      => 'Adult',
        self::TYPE_ASSOCIATE  => 'Associate',
        self::TYPE_CONCESSION => 'Concession',
        self::TYPE_FAMILY     => 'Family',
        self::TYPE_LIFE       => 'Life',
        self::TYPE_YOUTH      => 'Youth',
    ];

    protected $fillable = [
        'address_id',
        'cancellation_reason',
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

    public function contacts(): HasMany
    {
        return $this->hasMany(User::class, 'membership_id')->where('membership_type', 'contacts');
    }

    /**
     * @return HasMany
     */
    public function members(): HasMany
    {
        return $this->hasMany(User::class, 'membership_id')->where('membership_type', 'members');
    }
}

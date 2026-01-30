<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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
    public const string TYPE_CONSCESSION = 'conscession';
    public const string TYPE_FAMILY = 'family';
    public const string TYPE_YOUTH = 'youth';

    public const array TYPES = [
        self::TYPE_ADULT       => 'Adult',
        self::TYPE_CONSCESSION => 'Conscession',
        self::TYPE_FAMILY      => 'Family',
        self::TYPE_YOUTH       => 'Youth',
    ];

    protected $fillable = [
        'expiry',
        'status',
        'type',
    ];

    /**
     * @return MorphToMany
     */
    public function members(): MorphToMany
    {
        return $this->morphedByMany(Member::class, 'membershipable')->withPivot('order', 'type');
    }

    /**
     * @return
     */
    public function membershipable(): HasMany
    {
        return $this->hasMany(Membershipable::class);
    }

    /**
     * @return MorphToMany
     */
    public function users(): MorphToMany
    {
        return $this->morphedByMany(User::class, 'membershipable')->withPivot('order', 'type');
    }
}

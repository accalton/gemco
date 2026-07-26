<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
    public const string STATUS_EXPIRED = 'Expired';
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

    /**
     * @return BelongsToMany
     */
    public function contacts(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->as('membershipUser')
            ->wherePivot('type', 'contact')
            ->withPivot('relationship', 'type')
            ->orderByPivot('order');
    }

    /**
     * @return Attribute
     */
    public function isExpired(): Attribute
    {
        return Attribute::make(
            get: function () {
                $expiry = DateTime::createFromFormat('Y-m-d', $this->expiry);

                if ($expiry) {
                    $today = new DateTime('midnight');

                    return $expiry <= $today;
                }

                return false;
            }
        );
    }

    /**
     * @return BelongsToMany
     */
    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->as('membershipUser')
            ->wherePivot('type', 'member')
            ->withPivot('relationship', 'type')
            ->orderByPivot('order');
    }

    /**
     * @return HasMany
     */
    public function membership_user(): HasMany
    {
        return $this->hasMany(MembershipUser::class);
    }

    #[Scope]
    protected function current(Builder $query): void
    {
        $query->where('expiry', '>=', date('Y-m-d'));
    }

    #[Scope]
    protected function expired(Builder $query): void
    {
        $query->where('expiry', '<=', date('Y-m-d'));
    }
}

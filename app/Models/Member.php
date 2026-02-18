<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'address_id',
        'date_of_birth',
        'email',
        'name',
        'phone',
        'updated_at',
    ];

    /**
     * @return BelongsTo
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    /**
     * @return HasMany
     */
    public function identifications(): HasMany
    {
        return $this->hasMany(Identification::class);
    }

    /**
     * @return HasOne
     */
    public function membership(): HasOne
    {
        return $this->hasOne(Membership::class);
    }

    /**
     * @return BelongsToMany
     */
    public function memberships(): BelongsToMany
    {
        return $this->belongsToMany(Membership::class)->withPivot('relationship', 'type');
    }

    /**
     * @return HasMany
     */
    public function member_memberships(): HasMany
    {
        return $this->hasMany(MemberMembership::class);
    }
}

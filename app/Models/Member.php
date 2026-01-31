<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'date_of_birth',
        'email',
        'name',
        'phone',
    ];

    /**
     * @return BelongsToMany
     */
    public function membership(): BelongsToMany
    {
        return $this->belongsToMany(Membership::class);
    }

    /**
     * @return HasMany
     */
    public function member_memberships(): HasMany
    {
        return $this->hasMany(MemberMembership::class);
    }
}

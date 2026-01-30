<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

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
     * @return MorphMany
     */
    public function memberships(): MorphToMany
    {
        return $this->morphToMany(Membership::class, 'membershipable')->withPivot('order', 'type');
    }

    /**
     * @return HasMany
     */
    public function membershipables(): HasMany
    {
        return $this->hasMany(Membershipable::class, 'membershipable_id')->where('membershipable_type', static::class);
    }
}

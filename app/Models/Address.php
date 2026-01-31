<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Address extends Model
{
    use HasFactory;

    const array STATES = [
        'ACT' => 'Australian Capital Territory',
        'NT'  => 'Northern Territory',
        'QLD' => 'Queensland',
        'SA'  => 'South Australia',
        'TAS' => 'Tasmania',
        'VIC' => 'Victoria',
        'WA'  => 'Western Australia',
    ];

    protected $fillable = [
        'line1',
        'line2',
        'postcode',
        'state',
        'suburb',
    ];

    /**
     * @return HasMany
     */
    public function members(): HasMany
    {
        return $this->hasMany(Members::class);
    }
}

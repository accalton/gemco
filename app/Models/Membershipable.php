<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Membershipable extends Model
{
    const TYPE_CONTACT = 'contact';
    const TYPE_MEMBER = 'member';

    const TYPES = [
        self::TYPE_CONTACT => 'Contact',
        self::TYPE_MEMBER  => 'Member'
    ];

    protected $fillable = [
        'membershipable_id',
        'membershipable_type',
        'order',
        'type',
    ];

    /**
     * @return BelongsTo
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}

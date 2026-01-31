<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberMembership extends Model
{
    public const string TYPE_CONTACT = 'contact';
    public const string TYPE_MEMBER = 'member';

    public const array TYPES = [
        self::TYPE_CONTACT => 'Contact',
        self::TYPE_MEMBER  => 'Member'
    ];

    protected $fillable = [
        'member_id',
        'membership_id',
        'type',
    ];

    protected $table = 'member_membership';

    /**
     * @return BelongsTo
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * @return BelongsTo
     */
    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }
}

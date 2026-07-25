<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembershipUser extends Model
{
    public const string TYPE_CONTACT = 'contact';
    public const string TYPE_MEMBER = 'member';

    public const array TYPES = [
        self::TYPE_CONTACT => 'Contact',
        self::TYPE_MEMBER  => 'Member'
    ];

    protected $fillable = [
        'membership_id',
        'order',
        'relationship',
        'type',
        'user_id',
    ];

    protected $table = 'membership_user';

    /**
     * @return BelongsTo
     */
    public function membership(): BelongsTo
    {
        return $this->belongsTo(Membership::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

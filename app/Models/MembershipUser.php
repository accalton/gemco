<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MembershipUser extends Model
{
    public $table = 'membership_user';
    public const string TYPE_CONTACT = 'contact';
    public const string TYPE_MEMBER = 'member';

    public const array TYPES = [
        self::TYPE_CONTACT => 'Contact',
        self::TYPE_MEMBER => 'Member',
    ];

    public $incrementing = true;

    protected $fillable = [
        'membership_id',
        'order',
        'type',
        'user_id',
    ];

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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Identification extends Model
{
    use HasFactory;

    public const TYPE_FIRST_AID = 'first-aid';
    public const TYPE_RSA = 'rsa';
    public const TYPE_WORKING_WITH_CHILDREN = 'working-with-children';
    public const TYPE_VICTORIAN_INSTITUTE_OF_TEACHING = 'victorian-institute-of-teaching';
    public const TYPE_OTHER = 'other';

    public const TYPES = [
        self::TYPE_FIRST_AID => 'First Aid',
        self::TYPE_RSA => 'RSA',
        self::TYPE_WORKING_WITH_CHILDREN => 'Working With Children',
        self::TYPE_VICTORIAN_INSTITUTE_OF_TEACHING => 'Victorian Institute of Teaching',
        self::TYPE_OTHER => 'Other'
    ];

    protected $fillable = [
        'details',
        'expiry',
        'member_id',
        'number',
        'type',
        'upload',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

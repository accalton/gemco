<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Identification extends Model
{
    use HasFactory;

    public const TYPES = [
        'first-aid' => 'First Aid',
        'rsa' => 'RSA',
        'working-with-children' => 'Working With Children',
        'victorian-institute-of-teaching' => 'Victorian Institute of Teaching',
    ];

    protected $fillable = [
        'expiry',
        'issued',
        'number',
        'type',
        'upload',
    ];

    /**
     * @return BelongsTo
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}

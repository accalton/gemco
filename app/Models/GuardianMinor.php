<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuardianMinor extends Model
{
    protected $fillable = [
        'guardian_id',
        'user_id'
    ];

    protected $table = 'guardian_minor';

    /**
     * @return BelongsTo
     */
    public function guardian(): BelongsTo
    {
        return $this->belongsTo(User::class, 'guardian_id');
    }

    /**
     * @return BelongsTo
     */
    public function minor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'minor_id');
    }
}

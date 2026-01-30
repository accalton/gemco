<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Identification extends Model
{
    use HasFactory;

    public const TYPES = [
        'working-with-children' => 'Working With Children'
    ];

    protected $fillable = [
        'expiry',
        'issued',
        'number',
        'type',
        'upload',
    ];
}

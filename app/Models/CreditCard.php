<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditCard extends Model
{
    // region traits
    use HasFactory;
    // endregion

    // region variables
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'name' => 'string',
        'type' => 'string',
        'number' => 'string',
        'expiration_date' => 'date',
    ];
    // endregion

    // region relations
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    // endregion
}

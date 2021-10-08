<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // region traits
    use HasApiTokens, HasFactory, Notifiable;

    // endregion

    // region variables
    protected $guarded = [];

    public $timestamps = false;

    protected $casts = [
        'name' => 'string',
        'email' => 'string',
        'address' => 'string',
        'account' => 'string',
        'description' => 'string',
        'date_of_birth' => 'date',
        'checked' => 'boolean',
    ];
    // endregion

    // region relations
    public function creditCards(): HasMany
    {
        return $this->hasMany(CreditCard::class);
    }
    // endregion
}

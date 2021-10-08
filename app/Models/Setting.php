<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    // region traits
    use HasFactory;

    // endregion

    // region variables
    protected $guarded = [];

    public $timestamps = false;

    protected $primaryKey = 'key';

    public $incrementing = false;
    // endregion

    // region const
    const LAST_PEOPLE_INSERTED_INDEX = 'last_people_inserted_index';
    // endregion
}

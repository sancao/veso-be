<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Uuids;

class UserRole extends Model
{
    use Uuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'role_id'
    ];
}

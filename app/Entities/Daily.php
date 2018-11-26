<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class User.
 *
 * @package namespace App\Entities;
 */
class Daily extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'madaily','uuid','tendaily', 'diachi','sodienthoai', 'cap','dailyquanly'
    ];

    public function users()
    {
        return $this->hasMany('App\Entities\User', 'daily_id','id');
    }
}

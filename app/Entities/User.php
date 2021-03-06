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
class User extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','daily_id','uuid','username', 'email','phone', 'password','status','address','quyen'
    ];

    public function daily()
    {
        return $this->belongsTo('App\Entities\Daily', 'daily_id','id');
    }
}

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
class Chonso extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'soduthuong','uuid','tienduthuong', 'loduthuong','daiduthuong', 'mobild','hanmucconso',
        'tonghanmuc','menhgia','daily_id','user_id','menhgia10','menhgia20','menhgia50'
    ];
}

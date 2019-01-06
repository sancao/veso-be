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
class Nhapso extends Model implements Transformable
{
    use TransformableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','uuid','tuso', 'denso','menhgia','daily_id','user_id','created_at'
    ];

    public function daily()
    {
        return $this->belongsTo('App\Entities\Daily', 'daily_id','id');
    }

    public function user()
    {
        return $this->belongsTo('App\Entities\User', 'user_id','id');
    }
}

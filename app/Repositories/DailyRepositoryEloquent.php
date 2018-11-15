<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Entities\Daily;

use Illuminate\Support\Facades\DB;
/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class DailyRepositoryEloquent extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Daily::class;
    }

    public function list(){
        $list= Daily::where('dailyquanly',1);
        return $list->paginate(10);
    }

    public function create(array $arr){
        $daily=new Daily($arr);
        return $daily->save();
    }
}

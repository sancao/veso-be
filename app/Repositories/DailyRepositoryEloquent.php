<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Entities\Daily;
use App\Entities\User;
use Ramsey\Uuid\Uuid;

use Illuminate\Support\Facades\DB;

class DailyRepositoryEloquent extends BaseRepository
{
    public function model()
    {
        return Daily::class;
    }

    public function list($user_id=0,$text){
        $companies= Daily::where('dailyquanly',1)
        ->where('deleted',0);
        // return $list->paginate(10);

        // $companies= Daily::join('users', 'dailies.user_id','=','users.id')    
        // ->select('dailies.tendaily','dailies.diachi'
        // ,'dailies.madaily','dailies.sodienthoai','users.email'
        // ,'dailies.cap','dailies.dailyquanly','dailies.id');

        // $companies->where(['dailies.user_id' => $user_id]);      
        
        if($text){
            $companies->where(function ($query) use($text) {
                $query->orWhere('dailies.tendaily','LIKE', '%'.$text.'%')
                ->orWhere('dailies.madaily' ,'LIKE', '%'.$text.'%')
                ->orWhere('dailies.diachi' ,'LIKE', '%'.$text.'%');
                // ->orWhere('schedules.comment' ,'LIKE', '%'.$text.'%')                
                // ->orWhere('services.amount' ,'LIKE', '%'.$text.'%')
                // ->orWhere('services.service' ,'LIKE', '%'.$text.'%')
                // ->orWhere('schedules.date' ,'LIKE', '%'.$text.'%')
                // ->orWhere('schedules.status' ,'LIKE', '%'.$text.'%');
            });
        }

        return $companies
                ->orderBy('dailies.madaily', 'desc')
                ->paginate(10);
    }

    public function listAll($user_id=0){
        return Daily::where('dailyquanly',1)
        ->where('deleted',0)
        ->select('id as value','tendaily as text')
        ->get();
    }

    public function create(array $arr)
    {
        $daily=Daily::find($arr["id"]);
        if($daily){
            $daily->tendaily=$arr["tendaily"];
            $daily->diachi=$arr["diachi"];
            $daily->sodienthoai=$arr["sodienthoai"];
            $daily->cap=$arr["cap"];
            $daily->dailyquanly=1;
            $daily->save();
            return $daily;
        }else{
            $uuidDailyStr    = 'user.user-create' . rand(1000, 9999) . time();
            $uuidDaily       = Uuid::uuid3(Uuid::NAMESPACE_DNS, $uuidDailyStr);
            $daily=new Daily();
            $daily->tendaily=$arr["tendaily"];
            $daily->uuid=$uuidDaily->toString();
            $daily->diachi=$arr["diachi"];
            $daily->sodienthoai=$arr["sodienthoai"];
            $daily->cap="cap4";
            $daily->dailyquanly=1;
            $daily->madaily=self::getMaDaiLy("MDL0000");
            $daily->save();
            return $daily;
        }
    }

    private function getMaDaiLy($str){
        $last_daily=Daily::orderBy('id', 'desc')->first();
        if($last_daily){
            return $str.($last_daily->id+1);
        }

        return $str."1";
    }

    public function delete($id){
        if($id){
            $daily= Daily::find($id);
            $daily->deleted=1;
            return $daily->update();
        }
        
        return false;
    }
}

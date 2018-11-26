<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\UserRepository;
use App\Entities\User;
use App\Entities\Chonso;
use App\Entities\Naptien;
use App\Validators\UserValidator;

use Illuminate\Support\Facades\DB;
/**
 * Class UserRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class UserRepositoryEloquent extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    public function updateRole($param)
    {
        // uuid & role_list
        $userId = $param['id'];
        $roleList = json_decode($param['role_list'], true);

        // Remove all current roles
        UserRole::where('user_id', $userId)->delete();

        // Insert selected roles
        foreach ($roleList as $value) {
            $item = new UserRole();
            $item->user_id = $userId;
            $item->role_id = $value;
            $item->save();
        }
    }

    /**
     * Get all modules of current user
     * @param $userId
     * @return array|\Illuminate\Support\Collection
     */
    public function getRouteList($userId)
    {

         //---------------------------------------------------
        // Get Module Route list Array base on User ID
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        $result = DB::table('modules')
            ->leftJoin('role_modules', 'modules.id', '=', 'role_modules.module_id')
            ->leftJoin('roles', 'role_modules.role_id', '=', 'roles.id')
            ->leftJoin('user_roles', 'roles.id', '=', 'user_roles.role_id')
            ->where('user_roles.user_id', $userId)
            //->distinct()
            ->get(['modules.route']);

        $result = $result->pluck('route')->toArray();
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        return $result;
    }

    public function getRoleList($userId)
    {
        //---------------------------------------------------
        // Get Role ID list Array base on $id (user id)
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        $roleIdList = DB::table('user_roles')
            ->where('user_id', $userId)
            ->get(['user_roles.role_id']);

        $roleIdList = $roleIdList->pluck('role_id')->toArray();
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        //dd($roleIdList);

        //---------------------------------------------------
        // Get Module Role list Array base on Role ID list
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
        $result = DB::table('roles')
            ->whereIn('roles.id', $roleIdList)      // [1, 2, 4]
            ->get(['roles.name']);

        $result = $result->pluck('name')->toArray();
        //~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

        return $result;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function list($user_id=0,$text){
        $users= User::join('dailies', 'dailies.id','=','users.daily_id')    
        ->select('users.name','users.address','users.id','users.quyen'
        ,'users.phone','dailies.tendaily','dailies.id as daily_id','users.email');

        // $companies->where(['dailies.user_id' => $user_id]);      
        
        if($text){
            $users->where(function ($query) use($text) {
                $query->orWhere('dailies.tendaily','LIKE', '%'.$text.'%')
                ->orWhere('users.name' ,'LIKE', '%'.$text.'%')
                ->orWhere('users.phone' ,'LIKE', '%'.$text.'%')
                ->orWhere('users.address' ,'LIKE', '%'.$text.'%')           
                ->orWhere('users.email' ,'LIKE', '%'.$text.'%');
            });
        }

        return $users
                ->orderBy('users.name', 'desc')
                ->paginate(15);
    }

    public function create(array $arr){
        $user=User::with(['daily'])->find($arr["id"]);
        
        if($user)
        {
            $user->name=$arr["name"];
            $user->quyen=$arr["quyen"];
            $user->phone=$arr["phone"];
            $user->address=$arr["address"];
            $user->daily_id=$arr["daily_id"];
            $user->update();
        }else
        {
            $user=new User($arr);
            $user->save();
        }

        return $user;
    }
    
    //save chọn số 
    public function save_chonso(array $arr){
        $chonso=Chonso::with(['daily','user'])->find($arr["id"]);
        
        if($chonso)
        {
            $chonso->daily_id=$arr["daily_id"];
            $chonso->user_id=$arr["user_id"];
            $chonso->soduthuong=$arr["soduthuong"];
            $chonso->tienduthuong=$arr["tienduthuong"];
            $chonso->hanmucconso=$arr["hanmucconso"];
            $chonso->update();
        }else
        {
            $chonso=new Chonso($arr);
            $chonso->save();
        }

        return $chonso;
    }

    public function list_chonso(){
        $list= Chonso::where('deleted',0);
        return $list->paginate(10);
    }

    public function list_naptien(){
        $list= Naptien::where('trangthai',0);
        return $list->paginate(10);
    }

    public function check_email_unique($email){
        $user=User::where("email",$email)->first();
        if($user){
            return false;
        }

        return true;
    }

    public function check_phone_unique($phone){
        $user=User::where("phone",$phone)->first();
        if($user){
            return true;
        }

        return true;
    }
}

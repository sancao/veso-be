<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\UserRepository;
use App\Entities\User;
use App\Entities\Chonso;
use App\Entities\Nhapso;
use App\Entities\Giaoso;
use App\Entities\Naptien;
use App\Entities\RoleModule;
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

    public function getRouteList($quyen){
        $items = RoleModule::where('quyen',$quyen)
        ->select('route')->get();
        return $items;
    }

    public function findByEmailField($email){
        $item = User::with(['daily'])->where(['email'   => $email])->first();
        return $item;
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

        // $users->where(['dailies.user_id' => $user_id]);      
        
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
            return self::getUserDailyById($user->id);
        }else
        {
            $user=new User($arr);
            $user->save();
        }

        return $user;
    }

    private function getUserDailyById($id){
        $user= User::join('dailies', 'dailies.id','=','users.daily_id')    
        ->select('dailies.tendaily')
        ->where('users.id',$id)->first();

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

    public function delete_chonso($id){
        if($id){
            $so= Chonso::find($id);
            $so->deleted=1;
            return $so->update();
        }
        
        return false;
    }

    // nhập số
    public function save_nhapso(array $arr){
        $nhapso=Nhapso::find($arr["id"]);
        
        if($nhapso)
        {
            $nhapso->daily_id=$arr["daily_id"];
            $nhapso->user_id=$arr["user_id"];
            $nhapso->tuso=$arr["tuso"];
            $nhapso->denso=$arr["denso"];
            $nhapso->menhgia=$arr["menhgia"];
            $nhapso->update();
        }else
        {
            $nhapso=new Nhapso($arr);
            $nhapso->save();
        }

        return Nhapso::with(['daily','user'])->find($nhapso->id);
    }

    public function list_nhapso(){
        $list= Nhapso::where('deleted',0);
        return $list->paginate(10);
    }

    public function delete_nhapso($id){
        if($id){
            $so= Nhapso::find($id);
            $so->deleted=1;
            return $so->update();
        }
        
        return false;
    }

    // giao số
    public function save_giaoso(array $arr){
        $giaoso=Giaoso::find($arr["id"]);
        
        if($giaoso)
        {
            $giaoso->daily_id=$arr["daily_id"];
            $giaoso->user_id=$arr["user_id"];
            $giaoso->tuso=$arr["tuso"];
            $giaoso->denso=$arr["denso"];
            $giaoso->menhgia=$arr["menhgia"];
            $giaoso->update();
        }else
        {
            $giaoso=new Giaoso($arr);
            $giaoso->save();
        }

        return Giaoso::with(['daily','user'])->find($giaoso->id);
    }

    public function list_giaoso(){
        $list= Giaoso::with(['daily','user'])->where('deleted',0);
        return $list->paginate(10);
    }

    public function delete_giaoso($id){
        if($id){
            $so= Giaoso::find($id);
            $so->deleted=1;
            return $so->update();
        }
        
        return false;
    }

    ////////////////////////////////
    public function list_naptien(){
        $list= Naptien::where('trangthai',0);
        return $list->paginate(10);
    }

    public function check_email_unique($value){
        $user=User::where("email",$value)->first();
        if($user){
            return false;
        }else{
            $user=User::where("username",$value)->first();
            return $user==null;
        }

        return true;
    }

    public function check_phone_unique($phone){
        $user=User::where("phone",$phone)->first();
        if($user){
            return false;
        }

        return true;
    }
}

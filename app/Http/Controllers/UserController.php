<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterUser;
use App\Http\Requests\AddUser;
use App\Http\Requests\UpdateRole;
use App\Http\Requests\ListUser;
use App\Http\Requests\EditUser;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepositoryEloquent;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;
use Ramsey\Uuid\Uuid;

use Illuminate\Support\Facades\Log;
use App\Services\Helper;
use App\Services\Logger;

class UserController extends Controller
{
    protected $repository;

    public function __construct(UserRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Register user
     * @param RegisterUser $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request)
    {
        try {
            $uuidStr    = 'user.user-register' . rand(1000, 9999) . time();
            $uuid       = Uuid::uuid3(Uuid::NAMESPACE_DNS, $uuidStr);
            $params     = $request->all();
            $user=$this->repository->create([
                'id'            =>$params['id'],
                'uuid'          => $uuid->toString(),
                'daily_id'         => $params['daily_id'],
                'email'         => $params['email'],
                'name'          => $params['name'],
                'quyen'          => $params['quyen'],
                'username'      => $params['email'],
                'phone'      => $params['phone'],
                'address'    => $params['address'],
                'password'      => Hash::make("123"),
            ]);

            //---------- Log --------------
            Logger::log('info', __('user.log_register'), $params);
            //---------- Log --------------

            return Helper::jsonOK(__('common.ok'),$user);
        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        } catch (UnsatisfiedDependencyException $e) {
            report($e);
            return Helper::jsonNG(__('common.uuid_error'), $e->getMessage());
        }
    }

    /**
     * Login
     * @param AddUser $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(AddUser $request)
    {
        try {
            $email      = $request->get('email');
            $password   = $request->get('password');
            $item       = $this->repository->findByEmailField($email)->first();

            // Wrong email - return Fail result
            if (empty($item)) {
                return Helper::jsonNG(__('user.login_fail') . ' - Email', ['email' => $email]);
            }

            // Check password -> return Fail result
            if (!(Hash::check($password, $item->password))) {
                return Helper::jsonNG(__('user.login_fail') . ' - Password', ['email' => $email, 'password' => $password]);
            }

            //----------------------------------------
            // Login OK -> generate token or use existed token
            //----------------------------------------


            //----------------------------------------
            // 1. Get Permission Route List & Role List of logged user
            //----------------------------------------
            $routeList  = $this->repository->getRouteList($item->quyen);
            // $roleList   = $this->repository->getRoleList($item->id);

            //----------------------------------------
            // 2. Get expired datetime
            //----------------------------------------
            $date = new \DateTime();
            //date_add($date, date_interval_create_from_date_string('2 days'));
            date_add($date, date_interval_create_from_date_string('1 days'));
            $timestamp  = $date->getTimestamp();  

            //----------------------------------------
            // 3. Generate new token value
            //----------------------------------------
            $tokenContent = array(
                "exp" => $timestamp,
                "context"   => [
                    "user_id"       => $item->id,
                    "email"         => $item->email,
                    "name"          => $item->name,
                    "quyen"         => $item->quyen,
                    "client_id"     => $item->client_id,
                    'route_list'    => $routeList
                ]
            );
            $token = JWT::encode($tokenContent, config('app.jwt-key'));

            //------- Return success result -------
            $returnData   = [
                "user_id"       => $item->id,
                "token"         => $token,
                "quyen"         => $item->quyen,
                "client_id"     => $item->client_id,
                "cap"           => $item->daily_id==0?"cap0":$item->daily->cap,
                "route_list"     =>  $routeList
            ];

            //------- Log ---------
            Logger::log('info', __('user.login_success'), ['email' => $email, 'password' => $password, 'result' => $returnData]);
            //------- Log ---------

            return Helper::jsonOK(__('user.login_success'), $returnData);
        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        }
    }

    /**
     * Logout
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        return Helper::jsonOK(__('common.ok'));
    }

    /**
     * List registed users
     * @param ListUser $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listUser(Request $request)
    {
        try {
            // $param    = $request->validated();

            // Todo: still not format the result, not filter, paging...
            $users   = $this->repository->list(0,$request["text"]);

            $response = [
                'pagination' => [
                    'total' => $users->total(),
                    'per_page' => $users->perPage(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem()
                ],
                'data' => $users
            ];

            return Helper::jsonOK(__('common.ok'), $response);


            // $user = Auth::user();
            // dd($user);
            // $companies=$this->company->getCompanies(5,$request['text']); 
            // $trs_search=$request['text'];
            // $companies = Cache::remember('companies', 22*60, function() {
            //     return $this->company->getCompanies(5,$trs_search); 
            // });


            // $response = [
            //     'pagination' => [
            //         'total' => $companies->total(),
            //         'per_page' => $companies->perPage(),
            //         'current_page' => $companies->currentPage(),
            //         'last_page' => $companies->lastPage(),
            //         'from' => $companies->firstItem(),
            //         'to' => $companies->lastItem()
            //     ],
            //     'data' => $companies
            // ];
            
            // return $this->common->responseToJson(True,200,'success',$response);


        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        }
    }

    /**
     * Add user
     * @param RegisterUser $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editUser(Request $request)
    {
        try {
            $uuidStr    = 'user.user-add' . rand(1000, 9999) . time();
            $uuid       = Uuid::uuid3(Uuid::NAMESPACE_DNS, $uuidStr);
            $params     = $request->validated();

            $result     = $this->repository->create([
                'uuid'          => $uuid->toString(),
                'email'         => $params['email'],
                'name'          => $params['name'],
                'password'      => Hash::make($params['password']),
                'client_ids'    => $params['client_ids'],
            ]);

            return Helper::jsonOK(__('common.ok'), $result);
        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        } catch (UnsatisfiedDependencyException $e) {
            report($e);
            return Helper::jsonNG(__('common.uuid_error'), $e->getMessage());
        }
    }

    /**
     * Update user info
     * @param EditUser $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(EditUser $request)
    {
        try {
            $params = $request->validated();

            $updateParams   = [
                'name'              => $params['name']
            ];
            if (array_key_exists('password', $params)) {
                $updateParams['password']   = Hash::make($params['password']);
            }
            if (array_key_exists('client_ids', $params)) {
                $updateParams['client_ids']   = \GuzzleHttp\json_encode($params['client_ids'], true);
            }

            $item   = $this->repository->updateWhere(
                $updateParams,
                [
                    'field'     => 'uuid',
                    'value'     => $params['uuid'],
                ]
            );

            return Helper::jsonOK(__('common.ok'));
        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        }
    }

    /**
     * Update user role
     * @param UpdateRole $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateRole(UpdateRole $request)
    {
        try {
            $params = $request->validated();
            $this->repository->updateRole($params);

            return Helper::jsonOK(__('common.ok'));
        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        }
    }


    public function list_naptien(Request $request)
    {
        try {
            $list   = $this->repository->list_naptien();

            $response = [
                'pagination' => [
                    'total' => $list->total(),
                    'per_page' => $list->perPage(),
                    'current_page' => $list->currentPage(),
                    'last_page' => $list->lastPage(),
                    'from' => $list->firstItem(),
                    'to' => $list->lastItem()
                ],
                'data' => $list
            ];

            return Helper::jsonOK(__('common.ok'), $response);
        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        }
    }

    public function check_email_unique($value){
        try {
            $flag=$this->repository->check_email_unique($value);

            return Helper::jsonOK(__('common.ok'),$flag);
        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        }
    }

    public function check_phone_unique($value)
    {
        $flag=$this->repository->check_phone_unique($value);

        return response()->json(['data'=> $flag]);
    }
}

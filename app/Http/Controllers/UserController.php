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
    public function register(RegisterUser $request)
    {
        try {
            $uuidStr    = 'user.user-register' . rand(1000, 9999) . time();
            $uuid       = Uuid::uuid3(Uuid::NAMESPACE_DNS, $uuidStr);
            $params     = $request->validated();

            $this->repository->create([
                'uuid'          => $uuid->toString(),
                'email'         => $params['email'],
                'name'          => $params['name'],
                'password'      => Hash::make($params['password']),
                'client_ids'    => $params['client_ids'],
            ]);

            //---------- Log --------------
            Logger::log('info', __('user.log_register'), $params);
            //---------- Log --------------

            return Helper::jsonOK(__('common.ok'));
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
            $item       = $this->repository->findByField(['email'   => $email])->first();

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
            $routeList  = $this->repository->getRouteList($item->id);
            $roleList   = $this->repository->getRoleList($item->id);

            //----------------------------------------
            // 2. Get expired datetime
            //----------------------------------------
            $date = new \DateTime();
            //date_add($date, date_interval_create_from_date_string('2 days'));
            date_add($date, date_interval_create_from_date_string('2 days'));
            $timestamp  = $date->getTimestamp();

            //----------------------------------------
            // 3. Generate new token value
            //----------------------------------------
            $tokenContent = array(
                //"iss" => "http://example.org",
                "exp" => $timestamp,
                "context"   => [
                    "id"            => $item->id,
                    "email"         => $item->email,
                    "name"          => $item->name,
                    "route_list"    => $routeList,
                    //"role"  => $item->role,
                    "client_ids"    => $item->client_ids,
                ]
            );
            $token = JWT::encode($tokenContent, config('app.jwt-key'));

            //------- Log Message --------
            //$message    = __('user.log_login') . ' - ' . __('user.login_success');

            //------- Return success result -------
            $returnData   = [
                'token'         => $token,
                'route_list'    => base64_encode(json_encode($routeList)),
                'role_list'     => base64_encode(json_encode($roleList)),
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
    public function listUser(ListUser $request)
    {
        try {
            // $param    = $request->validated();

            // Todo: still not format the result, not filter, paging...
            $data   = $this->repository->all();

            return Helper::jsonOK(__('common.ok'), $data);
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
    public function add(RegisterUser $request)
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
}

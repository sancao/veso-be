<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterUser;
use Illuminate\Support\Facades\Hash;
use App\Repositories\DailyRepositoryEloquent;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;
use Ramsey\Uuid\Uuid;

use Illuminate\Support\Facades\Log;
use App\Services\Helper;
use App\Services\Logger;

class DailyController extends Controller
{
    protected $repository;

    public function __construct(DailyRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    public function add(Request $request)
    {
        // dd($request->all());
        try {
            // $uuidStr    = 'user.user-register' . rand(1000, 9999) . time();
            // $uuid       = Uuid::uuid3(Uuid::NAMESPACE_DNS, $uuidStr);
            $params     = $request->all();
            $user=$this->repository->create($params);
            //     'uuid'          => $uuid->toString(),
            //     'tendaily'          => $params['tendaily'],
            //     'diachi'      => $params['diachi'],
            //     'sodienthoai'      => $params['sodienthoai'],
            //     'matkhau'      => Hash::make("123"),
            //     'cap'    => $params['cap'],
            // ]);

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

    public function listDaily(Request $request)
    {
        try {
            $daily   = $this->repository->list(0,$request["text"]);

            $response = [
                'pagination' => [
                    'total' => $daily->total(),
                    'per_page' => $daily->perPage(),
                    'current_page' => $daily->currentPage(),
                    'last_page' => $daily->lastPage(),
                    'from' => $daily->firstItem(),
                    'to' => $daily->lastItem()
                ],
                'data' => $daily
            ];

            return Helper::jsonOK(__('common.ok'), $response);
        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        }
    }

    public function list_all()
    {
        try {
            $daily   = $this->repository->listAll(0);

            return Helper::jsonOK(__('common.ok'), $daily);
        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        }
    }
    
    public function editDaily(Request $request)
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

    public function delete(Request $request)
    {
        try {
            $result= $this->repository->delete($request['id']);
            return Helper::jsonOK(__('common.ok'), $result);
        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        } catch (UnsatisfiedDependencyException $e) {
            report($e);
            return Helper::jsonNG(__('common.uuid_error'), $e->getMessage());
        }
    }
}

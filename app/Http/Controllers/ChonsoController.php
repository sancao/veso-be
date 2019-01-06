<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterUser;
use Illuminate\Support\Facades\Hash;
use App\Repositories\UserRepositoryEloquent;
use Firebase\JWT\JWT;
use Firebase\JWT\SignatureInvalidException;
use Firebase\JWT\ExpiredException;
use Ramsey\Uuid\Uuid;

use Illuminate\Support\Facades\Log;
use App\Services\Helper;
use App\Services\Logger;

class ChonsoController extends Controller
{
    protected $repository;

    public function __construct(UserRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    public function add(Request $request)
    {
        try {
            $params     = $request->all();
            $chonso=$this->repository->save_chonso([
                'id'                => $params['id'],
                'daily_id'          => 1,
                'user_id'           => 1,
                'sodienthoai'     => $params['sodienthoai'],
                'soduthuong'      => $params['soduthuong'],
                'tienduthuong'      => $params['tienduthuong'],
                'hanmucconso'      => $params['hanmucconso'],
                'tonghanmuc'      => $params['tonghanmuc'],
                'ngayduthuong'      => $params['ngayduthuong'],
                'loduthuong'      => $params['loduthuong'],
                'daiduthuong'      => $params['daiduthuong'],
                'menhgia10'      => $params['menhgia10'],
                'menhgia20'      => $params['menhgia20'],
                'menhgia50'    => $params['menhgia50']
            ]);

            //---------- Log --------------
            Logger::log('info', __('user.log_register'), $params);
            //---------- Log --------------

            return Helper::jsonOK(__('common.ok'),$chonso);
        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        } catch (UnsatisfiedDependencyException $e) {
            report($e);
            return Helper::jsonNG(__('common.uuid_error'), $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $list   = $this->repository->list_chonso();

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

    public function delete(Request $request)
    {
        try {
            $result= $this->repository->delete_chonso($request['id']);
            return Helper::jsonOK(__('common.ok'), $result);
        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        } catch (UnsatisfiedDependencyException $e) {
            report($e);
            return Helper::jsonNG(__('common.uuid_error'), $e->getMessage());
        }
    }

    // nhập số
    public function add_nhapso(Request $request)
    {
        try {
            $uuidStr    = 'nhapso' . rand(1000, 9999) . time();
            $uuid       = Uuid::uuid3(Uuid::NAMESPACE_DNS, $uuidStr);
            $params     = $request->all();
            $chonso=$this->repository->save_nhapso([
                'id'                => $params['id'],
                'uuid'          => $uuid->toString(),
                'daily_id'          => 1,
                'user_id'           => 1,
                'tuso'     => $params['tuso'],
                'denso'      => $params['denso'],
                'menhgia'      => $params['menhgia'],
            ]);

            //---------- Log --------------
            Logger::log('info', __('user.log_register'), $params);
            //---------- Log --------------

            return Helper::jsonOK(__('common.ok'),$chonso);
        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        } catch (UnsatisfiedDependencyException $e) {
            report($e);
            return Helper::jsonNG(__('common.uuid_error'), $e->getMessage());
        }
    }

    public function list_nhapso(Request $request)
    {
        try {
            $list   = $this->repository->list_nhapso();

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

    public function delete_nhapso(Request $request)
    {
        try {
            $result= $this->repository->delete_nhapso($request['id']);
            return Helper::jsonOK(__('common.ok'), $result);
        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        } catch (UnsatisfiedDependencyException $e) {
            report($e);
            return Helper::jsonNG(__('common.uuid_error'), $e->getMessage());
        }
    }

    // giao số
    public function add_giaoso(Request $request)
    {
        try {
            $uuidStr    = 'giaoso' . rand(1000, 9999) . time();
            $uuid       = Uuid::uuid3(Uuid::NAMESPACE_DNS, $uuidStr);
            $params     = $request->all();
            $chonso=$this->repository->save_giaoso([
                'id'                => $params['id'],
                'uuid'          => $uuid->toString(),
                'daily_id'          => 1,
                'user_id'           => 1,
                'tuso'     => $params['tuso'],
                'denso'      => $params['denso'],
                'menhgia'      => $params['menhgia'],
            ]);

            //---------- Log --------------
            Logger::log('info', __('user.log_register'), $params);
            //---------- Log --------------

            return Helper::jsonOK(__('common.ok'),$chonso);
        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        } catch (UnsatisfiedDependencyException $e) {
            report($e);
            return Helper::jsonNG(__('common.uuid_error'), $e->getMessage());
        }
    }

    public function list_giaoso(Request $request)
    {
        try {
            $list   = $this->repository->list_giaoso();

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

    public function delete_giaoso(Request $request)
    {
        try {
            $result= $this->repository->delete_giaoso($request['id']);
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

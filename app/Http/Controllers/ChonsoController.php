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
                'mobile'           => "0917044715",
                'soduthuong'      => $params['soduthuong'],
                'tienduthuong'      => $params['tienduthuong'],
                'hanmucconso'      => $params['hanmucconso'],
                'tonghanmuc'      => $params['tonghanmuc'],
                'ngayduthuong'      => $params['ngayduthuong'],
                'loduthuong'      => $params['loduthuong'],
                'daiduthuong'      => $params['daiduthuong'],
                'menhgia'      => $params['menhgia10'],
                'menhgia10'      => $params['menhgia10'],
                'menhgia20'      => $params['menhgia20'],
                'menhgia50'    => $params['menhgia50'],
                'daimacdinh'    => $params['daimacdinh'],
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
    
    public function edit(Request $request)
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
}

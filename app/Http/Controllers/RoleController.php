<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Helper;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

use App\Http\Requests\Role\ListRole;
use App\Http\Requests\Role\AddRole;
use App\Http\Requests\Role\EditRole;
use App\Http\Requests\Role\UpdateModule;
use App\Repositories\UserRepositoryEloquent;

class RoleController extends Controller
{
    protected $repository;

    public function __construct(UserRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * List Roles
     * @param ListRole $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listData(ListRole $request)
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
     * Add Role
     * @param AddRole $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(AddRole $request)
    {
        try {
            $uuidStr    = 'user.role'. rand(1000, 9999) . time();
            $uuid       = Uuid::uuid3(Uuid::NAMESPACE_DNS, $uuidStr);
            $params     = $request->validated();

            $result = $this->repository->create([
                'uuid'              => $uuid->toString(),
                'name'              => $params['name'],
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
     * Edit Role
     * @param EditRole $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(EditRole $request)
    {
        try {
            $params = $request->validated();

            $item   = $this->repository->updateWhere(
               [
                   'name'      => $params['name'],
               ],
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
     * Delete Role
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        try {
            $params = $request->validate([
               'uuid' => 'required',
           ]);

            $this->repository->deleteRole($params['uuid']);

            return Helper::jsonOK(__('common.ok'));
        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        }
    }

    /**
     * Update Modules of Role
     * @param UpdateModule $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateModule(UpdateModule $request)
    {
        try {
            $params = $request->validated();
            $this->repository->updateModule($params);

            return Helper::jsonOK(__('common.ok'));
        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        }
    }
}

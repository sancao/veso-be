<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Module\ListModule;
use App\Http\Requests\Module\AddModule;
use App\Http\Requests\Module\EditModule;

use App\Repositories\UserRepositoryEloquent;
use App\Services\Helper;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\Exception\UnsatisfiedDependencyException;

class ModuleController extends Controller
{
    protected $repository;

    public function __construct(UserRepositoryEloquent $repository)
    {
        $this->repository = $repository;
    }

    /**
     * List All Module
     * @param ListModule $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function listModule(ListModule $request)
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
     * Add Module
     * @param AddModule $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(AddModule $request)
    {
        try {
            $uuidStr = 'user.module' . rand(1000, 9999) . time();
            $uuid = Uuid::uuid3(Uuid::NAMESPACE_DNS, $uuidStr);

            $params      = $request->validated();

            $result = $this->repository->create([
                // Required
                'uuid'              => $uuid->toString(),
                'route'             => $params['route'],
                'name'              => $params['name'],
            ]);

            return Helper::jsonOK(__('common.ok'), $result->toArray());
        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        } catch (UnsatisfiedDependencyException $e) {
            report($e);
            return Helper::jsonNG(__('common.uuid_error'), $e->getMessage());
        }
    }

    /**
     * Edit Module
     * @param EditModule $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(EditModule $request)
    {
        try {
            $params = $request->validated();

            $item   = $this->repository->updateWhere(
                [
                    'route'     => $params['route'],
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
     * Delete Module
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request)
    {
        try {
            $params = $request->validate([
                'uuid' => 'required',
            ]);
            $this->repository->deleteWhere(['uuid' => $params['uuid']]);

            return Helper::jsonOK(__('common.ok'));
        } catch (\Exception $e) {
            report($e);
            return Helper::jsonNG(__('common.err_code'), $e->getMessage());
        }
    }
}

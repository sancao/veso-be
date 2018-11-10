<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\RoleRepository;
use App\Entities\Role;
use App\Validators\RoleValidator;

/**
 * Class RoleRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class RoleRepositoryEloquent extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Role::class;
    }

    public function updateModule($param)
    {

        // uuid & role_list
        $roleId     = $param['id'];
        $moduleList = json_decode($param['module_list'], true);

        // Remove all current roles
        RoleModule::where('role_id', $roleId)->delete();

        // Insert selected roles
        foreach ($moduleList as $value) {
            $item            = new RoleModule();
            $item->role_id   = $roleId;
            $item->module_id = $value;
            $item->save();
        }

        return true;
    }

    /**
     * Update Role table
     * @param $update
     * @param $where
     * @return mixed
     */
    public function updateWhere($update, $where)
    {
        return $this->model->where($where['field'], $where['value'])->update($update);
    }

    /**
     * Delete a Role
     * @param $uuid
     */
    public function deleteRole($uuid)
    {
        $item = $this->findWhere(['uuid' => $uuid], ['id'])->first();
        if (!empty($item)) {
            $this->delete($item->id);
            RoleModule::where('role_id', $item->id)->delete();
        }
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}

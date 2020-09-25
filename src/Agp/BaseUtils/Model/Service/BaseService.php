<?php


namespace Agp\BaseUtils\Model\Service;


use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

abstract class BaseService
{
    /**
     * @param Model $model
     * @return Model
     * @throws Exception
     */
    public function updateOrStore($model)
    {
        return ($model->exists) ? $this->update($model) : $this->store($model);
    }

    /**
     * @param Model $model
     * @return Model
     * @throws Exception
     */
    public function update($model)
    {
        return $this->save($model);
    }

    private function save($model)
    {
        DB::beginTransaction();
        $this->beforeSave($model);
        try {
            $model->synchronized ? $model->push() : $model->save();
            DB::commit();
            $this->afterSave($model);
            return $model;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function beforeSave($model)
    {
    }

    public function afterSave($model)
    {
    }

    /**
     * @param Model $model
     * @return Model
     * @throws \Exception
     */
    public function store($model)
    {
        return $this->save($model);
    }

    /**
     * @param Model $model
     * @throws CustomException
     * @throws Exception
     */
    public function destroy(Model $model)
    {
        DB::beginTransaction();
        try {
            $removed = $model->delete();
            if (!$removed)
                throw new Exception("Não foi possível remover.");

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}

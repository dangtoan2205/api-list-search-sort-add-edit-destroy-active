<?php

namespace App\Services;

use App\Exceptions\DDException;
use App\Helpers\LogHelper;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService
{
    /**
     * @var Model
     */
    protected $model;

    protected $repository;

    /**
     * Construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->setRepository();
        $this->setModel();
    }

    /**
     * Returns Model.
     *
     * @return object
     */
    abstract public function getModel();

    /**
     * Dynamically call the default driver instance.
     *
     * @param string $method     Method.
     * @param array  $parameters Parameters.
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        try {
            return $this->repository->{$method}(...$parameters);
        } catch (DDException $ex) {
            LogHelper::logTrace($ex);

            throw $ex;
        }
    }
    /**
     * Returns Model.
     *
     * @return object
     */
    abstract public function getRepository();

    /**
     * @return object
     */
    public function setRepository()
    {
        return $this->getRepository();
    }

    /**
    *
    * @return object
    */
    public function setModel()
    {
        $model = app()->make($this->getModel());
        if (!$model instanceof Model) {
            throw new \Exception("[BaseService->setModel:" . __LINE__ . "] Class {$this->getModel()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }
}

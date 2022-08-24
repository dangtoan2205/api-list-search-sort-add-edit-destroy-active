<?php

namespace App\Repositories;

use App\Exceptions\DDException;
use App\Helpers\LogHelper;
use Carbon\Carbon;
use DB;
use Illuminate\Container\Container as App;
use Illuminate\Database\Eloquent\Model;
use Str;

abstract class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->app = new App();
        $this->setModelClass();
    }

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
            return $this->model->{$method}(...$parameters);
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
    abstract public function getModel();

    /**
     * @return Model
     */
    public function setModelClass()
    {
        $model = $this->app->make($this->getModel());
        if (!$model instanceof Model) {
            throw new DDException("Class {$this->getModel()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->model = $model;
    }

    /**
     * Get list model.
     *
     * @param mixed $conditions Conditions.
     *
     * @return Collection $entities
     */
    public function list($conditions)
    {
        try {
            // select
            $entities = $this->model->select($conditions['select'] ?? ['*']);

            // relations
            if (isset($conditions['with'])) {
                $entities = $entities->with($conditions['with']);
            }

            // realtion counts
            if (isset($conditions['with_count'])) {
                $entities = $entities->withCount($conditions['with_count']);
            }

            // filter data
            if (count($conditions)) {
                $entities = $this->filter($entities, $conditions);
            }

            // order by row
            if (isset($conditions['order_by_row'], $conditions['order_row_type'])) {
                $entities = $entities->orderByRaw($conditions['order_by_row'], $conditions['order_row_type'] ? 'asc' : 'desc');
            }

            // order by
            if (isset($conditions['order_by'], $conditions['order_type'])) {
                $entities = $entities->orderBy($conditions['order_by'], $conditions['order_type'] ? 'asc' : 'desc');
            }

            // first
            if (isset($conditions['first'])) {
                return $entities->first();
            }

            // all
            if (isset($conditions['all'])) {
                return $entities->get();
            }

            // limit
            if (isset($conditions['limit'])) {
                return $entities->paginate($conditions['limit']);
            }

            return $entities->get();
        } catch (DDException $ex) {
            LogHelper::logTrace($ex);

            throw $ex;
        }
    }

    /**
     * Create model.
     *
     * @param array $data Data.
     *
     * @return Model
     */
    public function create(array $data = [])
    {
        return $this->model->create($data);
    }

    /**
     * Get model detail.
     *
     * @param Model $entity    EntityModel.
     * @param array $relations Relations.
     *
     * @return Model
     */
    public function detail(Model $entity, array $relations = [])
    {
        if (count($relations)) {
            return $entity->load($relations);
        }

        return $entity;
    }

    /**
     * Update model.
     *
     * @param Model $entity Entity.
     * @param array $data   Data.
     *
     * @return Model
     */
    public function update(Model $entity, array $data = [])
    {
        $entity->update($data);

        return $entity;
    }

    /**
     * Update or create model.
     *
     * @param array $condition Condition.
     * @param array $data      Data.
     *
     * @return Model
     */
    public function updateOrCreate(array $condition = [], array $data = [])
    {
        return $this->model->updateOrCreate($condition, $data);
    }

    /**
     * Delete model.
     *
     * @param Model $entity EntityModel.
     *
     * @return void
     */
    public function delete(Model $entity)
    {
        $entity->delete();
    }

    /**
     * Synchro model relation with data.
     *
     * @param Model $entity   Entity.
     * @param mixed $relation Relation.
     * @param array $data     Data.
     *
     * @return void
     */
    public function sync(Model $entity, $relation, array $data = [])
    {
        $entity->$relation()->sync($data);
    }

    /**
     * Get model count.
     *
     * @return integer
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * Get model total.
     *
     * @param mixed $field Field.
     *
     * @return integer
     */
    public function total($field)
    {
        return $this->model->sum($field);
    }

    /**
     * Insert multiple values.
     *
     * @param mixed $data Data.
     *
     * @return mixed
     */
    public function insert($data)
    {
        $data = array_map(function ($item) {
            $item['created_at'] = Carbon::now()->format('Y-m-d H:i:s');
            $item['updated_at'] = Carbon::now()->format('Y-m-d H:i:s');

            return $item;
        }, $data);

        return $this->model->insert($data);
    }

    /**
     * Group model by column.
     *
     * @param string $field Fields.
     *
     * @return mixed
     */
    public function groupBy(string $field)
    {
        $raw = $field . ', count(' . $field . ') as ' . $field . '_count';

        return $this->model->select(DB::raw($raw))->groupBy($field)->get();
    }

    /**
     * Find model by id.
     *
     * @param mixed $id        ID.
     * @param array $relations Relations.
     *
     * @return Model
     */
    public function findOrFail($id, array $relations = [])
    {
        $entity = $this->model->findOrFail($id);

        if (count($relations)) {
            return $entity->load($relations);
        }

        return $entity;
    }

    /**
     * Find model by id.
     *
     * @param mixed $id        ID.
     * @param array $relations Relations.
     *
     * @return Model
     */
    public function find($id, array $relations = [])
    {
        $entity = $this->model->find($id);

        if (count($relations)) {
            return $entity->load($relations);
        }

        return $entity;
    }

    /**
     * Find by condition .
     *
     * @param mixed $condition Condition.
     * @param array $relations Relations.
     *
     * @return object $entities
     */
    public function findByCondition($condition, array $relations = [])
    {
        $entities = $this->model->select($this->model->selectable);

        if (count($relations)) {
            $entities = $entities->with($relations);
        }

        if (count($condition)) {
            foreach ($condition as $key => $value) {
                $entities = $this->search($entities, $key, $value);
            }
        }

        return $entities;
    }

    /**
     * Get all data.
     *
     * @return  List of Model
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get model's fillable attribute.
     *
     * @return array
     */
    public function getFillable()
    {
        return $this->model->getFillable();
    }

    /**
     * Batch update.
     *
     * @param array $condition Condition.
     * @param array $data      Data.
     * @return mixed
     */
    public function batchUpdate(array $condition, array $data)
    {
        $model = $this->model;
        if (count($condition) && method_exists($this, 'search')) {
            foreach ($condition as $key => $value) {
                $model = $this->search($model, $key, $value);
            }
        }

        return $model->update($data);
    }

    /**
     * Cache the query result.
     *
     * @param string $method    Method.
     * @param mixed  ...$params Params.
     *
     * @return mixed cached query result
     */
    public function cache(string $method, ...$params)
    {
        if (!method_exists($this, $method)) {
            throw new DDException("Method doesn't exist");
        }
        $name = Str::singular($this->model->getTable()) . '_' . $method;
        $cacheByKey = config('constant.cache_expired.' . $name);
        $expired = $cacheByKey ? $cacheByKey : config('constant.cache_expired.default', 0);

        return cache()->remember($name, $expired, function () use ($method, $params) {
            return $this->$method(...$params);
        });
    }

    /**
     * Filter query.
     *
     * @param mixed $query Query.
     * @param array $data  Data.
     *
     * @return mixed
     */
    public function filter($query, array $data = [])
    {
        try {
            if (count($data) && method_exists($this, 'search')) {
                foreach ($data as $key => $value) {
                    $query = $this->search($query, $key, $value);
                }
            }

            return $query;
        } catch (DDException $ex) {
            LogHelper::logTrace($ex);

            throw $ex;
        }
    }
}

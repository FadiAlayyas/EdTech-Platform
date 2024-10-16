<?php

namespace App\Repositories\Base;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class EloquentBaseRepository implements BaseRepository
{
    protected Model $model;
    public string $filterType = '';

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function __call($function, $args)
    {
        $functionType = strtolower(substr($function, 0, 3));
        $propName = lcfirst(substr($function, 3));
        switch ($functionType) {
            case 'get':
                if (property_exists($this, $propName)) {
                    return $this->$propName;
                }
                break;
            case 'set':
                if (property_exists($this, $propName)) {
                    $this->$propName = $args[0];
                }
                break;
        }
    }

    public function findOrFail(int $id, ?array $with = null, ?array $order = null): ?Model
    {
        $model = $this->find($id, $with, $order);
        return $model ?? abort(404);
    }

    public function find(int $id, ?array $with = null, ?array $order = null, array $columns = ['*']): ?Model
    {
        $query = $this->model->newQuery();

        $this->processQuery($query, $with, $order);

        return $query->find($id, $columns);
    }

    public function all(array $columns = ['*'], ?array $with = null): Collection
    {
        $query = $this->model->newQuery();

        $this->processQuery($query, $with);

        return $query->orderBy('id', 'DESC')->get($columns);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(array $data, array $conditions): bool
    {
        return $this->model->where($conditions)->update($data);
    }

    public function updateOrCreate(array $conditions, array $data): Model
    {
        return $this->model->updateOrCreate($conditions, $data);
    }

    public function destroy(array $conditions): ?bool
    {
        return $this->model->where($conditions)->delete();
    }

    public function destroyMany(array $values, string $column = 'id'): ?bool
    {
        return $this->model->whereIn($column, $values)->delete();
    }

    public function findByMany(array $ids, ?array $with = null, array $columns = ['*']): Collection
    {
        $query = $this->model->query();

        $query->whereIn("id", $ids);

        $this->processQuery($query, $with);

        return $query->get($columns);
    }

    public function insert(array $data)
    {
        return $this->model->insert($data);
    }

    protected function processQuery(&$query, $with = null, $order = null): void
    {
        if ($with) {
            $query->with($with);
        }

        if (method_exists($this->model, 'locales')) {
            $query->withLocale();
        }

        if ($order && is_array($order)) {
            foreach ($order as $key => $sort) {
                $query->orderBy($key, $sort);
            }
        }
    }
}

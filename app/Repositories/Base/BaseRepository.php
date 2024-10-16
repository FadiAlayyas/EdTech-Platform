<?php

namespace App\Repositories\Base;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseRepository
{

    public function find(int $id, ?array $with = null, ?array $order = null, array $columns = ['*']): ?Model;

    public function findOrFail(int $id, ?array $with = null, ?array $order = null): ?Model;

    public function all(array $columns = ['*'], ?array $with = null): Collection;

    public function create(array $data): Model;

    public function update(array $data, array $conditions): bool;

    public function updateOrCreate(array $conditions, array $data): Model;

    public function destroy(array $conditions): ?bool;

    public function destroyMany(array $values, string $column = 'id'): ?bool;

    public function findByMany(array $ids, ?array $with = null, array $columns = ['*']): Collection;

    public function insert(array $data);
}

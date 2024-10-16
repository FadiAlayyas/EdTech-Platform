<?php

namespace App\Repositories\Base\Search;

use Illuminate\Database\Eloquent\Collection;

interface SearchableRepository
{
    public function search(string $query , array $with): Collection;
}
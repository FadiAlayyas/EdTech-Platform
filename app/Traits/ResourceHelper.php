<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;

trait ResourceHelper
{
    protected static function resource($data, $resource)
    {
        if (!$data instanceof Collection) {
            return new $resource($data);
        }
        if ($data instanceof Collection) {
            return $resource::collection($data);
        }
    }
}

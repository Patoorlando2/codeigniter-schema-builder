<?php

namespace App\Helpers;

use App\Libraries\SchemaBuilder;

if (!function_exists('forge_schema')) {
    function forge_schema(string $table, callable $callback)
    {
        $builder = new SchemaBuilder();
        $callback($builder);
        $builder->create($table, $callback);
    }
}

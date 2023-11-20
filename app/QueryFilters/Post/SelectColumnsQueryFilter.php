<?php

namespace App\QueryFilters\Post;

use Closure;
use Illuminate\Support\Facades\Schema;

class SelectColumnsQueryFilter
{
    public function handle($query, Closure $next)
    {
        $requestedColumns = request()->query("columns");

        if (!$requestedColumns) {
            return $next($query);
        }
    
        $availableColumns = collect(explode(',', $requestedColumns))
            ->filter(fn ($column) => Schema::hasColumn($query->getModel()->getTable(), $column))
            ->toArray();
    
        if (!empty($availableColumns)) {
            $query->select($availableColumns);
        }
    
        return $next($query);
    }
}

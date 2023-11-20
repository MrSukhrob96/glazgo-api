<?php

namespace App\QueryFilters\Post;

use Closure;

class SortQueryFilter
{
    public function handle($query, Closure $next)
    {
        if (!request()->has('sort')) 
        {
            return $next($query);
        }

        $sortDirection = strtoupper(request()->input('sort'));

        if ($sortDirection === 'DESC' || $sortDirection === 'ASC') 
        {
            $query->orderBy('created_at', $sortDirection);
        }

        return $next($query);
    }

}
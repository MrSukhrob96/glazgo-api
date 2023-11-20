<?php

namespace App\Repositories\Interfaces;

use App\Core\Interfaces\CoreRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PostRepositoryInterface extends CoreRepositoryInterface
{
    public function getPaginatedSortedPosts(int $limit, int $offset): ?LengthAwarePaginator;
}
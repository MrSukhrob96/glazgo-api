<?php

namespace App\Repositories;

use App\Core\CoreRepository;
use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Pipeline\Pipeline;

class PostRepository extends CoreRepository implements PostRepositoryInterface
{
    public function __construct(Post $post)
    {
        parent::__construct($post);
    }


    /**
     * Method getPaginatedSortedPosts
     * 
     * @param int $limit
     * @param int $offset
     * @return LengthAwarePaginator
     */
    public function getPaginatedSortedPosts(int $limit, int $offset): ?LengthAwarePaginator
    {
        $query = $this->model->query();

        $posts = app(Pipeline::class)
            ->send($query)
            ->through([
                \App\QueryFilters\Post\SortQueryFilter::class,
                \App\QueryFilters\Post\SelectColumnsQueryFilter::class
            ])
            ->thenReturn();

        return $posts->paginate($limit, ['*'], 'page', $offset);
    }
}

<?php

namespace App\Services;

use App\Core\CoreService;
use App\DTO\Post\CreatePostDTO;
use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Services\Interfaces\PostServiceInterface;
use Illuminate\Contracts\Cache\Repository as CacheRepositoryInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class PostService extends CoreService implements PostServiceInterface
{

    public function __construct(
        public readonly PostRepositoryInterface $postRepository,
        public readonly CacheRepositoryInterface $cacheRepository,
    ) {
    }

    /**
     * Method getPostsWithPagination
     * 
     * @param int $limit
     * @param int $offset
     */
    public function getAllPosts($limit, $offset)
    {
        $cacheKey = "posts{$limit}{$offset}";

        if (request()->filled('sort')) {
            $cacheKey .= request()->query('sort');
        }

        return $this->cacheRepository->remember(
            $cacheKey,
            now()->addMinutes(5),
            function () use ($limit, $offset, $cacheKey) {
                $posts = $this->postRepository->getPaginatedSortedPosts($limit, $offset);

                if ($posts->isNotEmpty()) {
                    return $posts;
                }

                $this->cacheRepository->forget($cacheKey);
                return collect();
            }
        );
    }

    /**
     * Method findPostById
     * 
     * @param int $id
     * @return Post
     */
    public function findPostById(int $id): ?Post
    {
        return $this->postRepository->findById($id);
    }

    /**
     * Method createPost
     * 
     * @param CreatePostDTO $dto
     * @return ?Post
     */
    public function createPost(CreatePostDTO $dto): ?Post
    {
        return $this->postRepository->firstOrCreate(
            data: $dto->toArray(),
            where: ["title" => $dto->title]
        );
    }

    /**
     * Method fetchPosts
     * 
     * @param 
     */
    public function featchPosts(int $limit, int $offset, array $fields = []): ?Response
    {
        $fields = implode(',', $fields);

        return Http::withoutVerifying()
            ->withOptions(["verify" => false])
            ->get("https://techcrunch.com/wp-json/wp/v2/posts?per_page={$limit}&offset={$offset}&_fields=$fields");
    }
}
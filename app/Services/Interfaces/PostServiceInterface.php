<?php

namespace App\Services\Interfaces;

use App\DTO\Post\CreatePostDTO;
use App\Models\Post;
use Illuminate\Http\Client\Response;

interface PostServiceInterface
{
    public function createPost(CreatePostDTO $dto): ?Post;
    public function getAllPosts(int $limit, int $offset);
    public function featchPosts(int $limit, int $offset, array $fields = []): ?Response;
}

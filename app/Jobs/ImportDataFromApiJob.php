<?php

namespace App\Jobs;

use App\DTO\Post\CreatePostDTO;
use App\Services\Interfaces\PostServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class ImportDataFromApiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(PostServiceInterface $postService): void
    {
        $perPage = 100;
        $requestedCount = 140;
        $totalArticles = min($requestedCount, 140);

        $articles = collect();

        $offset = 0;
        while ($totalArticles > 0) {
            $count = min($perPage, $totalArticles);
            $response = $postService->featchPosts($count, $offset, ["title", "date", "content"]);

            if ($response->successful()) {
                $articles = $articles->merge($response->json());
                $totalArticles -= $count;
                $offset += $count;
            }
        }

        $this->processArticles($articles, $postService);
    }

    private function processArticles($articles, $postService): void
    {
        $articles->each(function ($article) use ($postService) {
            $dto = new CreatePostDTO([
                "title" => $article['title']['rendered'],
                "created_at" => $article["date"],
                "body" => $article['content']['rendered']
            ]);

            $postService->createPost($dto);
        });
    }
}

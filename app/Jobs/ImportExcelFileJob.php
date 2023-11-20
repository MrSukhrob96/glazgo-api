<?php

namespace App\Jobs;

use App\DTO\Post\CreatePostDTO;
use App\Services\Interfaces\PostServiceInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Spatie\SimpleExcel\SimpleExcelReader;

class ImportExcelFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     */
    public function __construct(
        private string $filePath
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(PostServiceInterface $postService): void
    {
        SimpleExcelReader::create($this->filePath)
            ->getRows()
            ->each(function (array $item) use ($postService) {
                $dto = new CreatePostDTO($item);
                $postService->createPost($dto);
            });

        Storage::delete($this->filePath);
    }
}

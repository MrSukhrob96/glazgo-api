<?php

namespace App\Http\Controllers\API\V1;


use App\Http\Controllers\Controller;
use App\Http\Requests\Post\CreatePortRequest;
use App\Http\Resources\Post\PostsCollection;
use App\Jobs\ImportDataFromApiJob;
use App\Jobs\ImportExcelFileJob;
use App\Jobs\RemoveCacheJob;
use App\Services\Interfaces\FileUploadServiceInterface;
use App\Services\Interfaces\PostServiceInterface;

class PostControler extends Controller
{

    public function __construct(
        private readonly PostServiceInterface $postService,
        private readonly FileUploadServiceInterface $fileUploadService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $posts = $this->postService->getAllPosts($this->getLimit(), $this->getOffset());

            return response()->success(
                "success",
                new PostsCollection($posts)
            );
        } catch (\Exception $ex) {
            return response()->error($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePortRequest $request)
    {
        try {
            RemoveCacheJob::dispatch("posts*");

            if ($request->hasFile("articles")) 
            {
                $filePath = $this->fileUploadService->upload($request->file('articles'), 'articles');

                ImportExcelFileJob::dispatch($filePath)->onQueue("uploads");

                return response()->success("success");
            }

            ImportDataFromApiJob::dispatch()->onQueue("uploads");

            return response()->success("success");
        } catch (\Exception $ex) {
            return response()->error($ex->getMessage());
        }
    }
}

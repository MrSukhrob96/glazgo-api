<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;
 

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(RepositoryServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro("error", function (string $message = '', mixed $errors = [], $status = 400, mixed $data = []) {
            return Response::make([
                "status" => false,
                "body" => $data,
                "errors" => $errors,
                "message" => $message
            ], $status);
        });

        Response::macro("success", function (string $message = '', mixed $data = [], $status = 200, mixed $errors = []) {
            return Response::make([
                "status" => true,
                "body" => $data,
                "errors" => $errors,
                "message" => $message
            ], $status);
        });
    }
}

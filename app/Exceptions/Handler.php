<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, Request $request) {
            return response()->error('File size is too large', [], 500);
        });
        
        $this->renderable(function (\Illuminate\Database\QueryException $e, Request $request) {
            return response()->error('error with a connection database!', [], 500);
        });

    }
}

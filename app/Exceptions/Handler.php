<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Handle Custom Exceptions Here
     */
    public function render($request, Throwable $e)
    {
        //Model Not Found Exception
        if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            if (request()->wantsJson()) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Record Not Found !!!'
                ], 404);
            }
        }

        return parent::render($request, $e);
    }
}

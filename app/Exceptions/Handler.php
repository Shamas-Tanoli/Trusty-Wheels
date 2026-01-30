<?php

namespace App\Exceptions;

use Throwable;
use App\Helpers\ApiResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
{
    if ($request->expectsJson()) { 

     
        if ($e instanceof ValidationException) {
            return ApiResponse::error(
                'Validation failed',
                422,
                $e->errors()
            );
        }

   
        if ($e instanceof ModelNotFoundException) {
            return ApiResponse::error('Resource not found', 404);
        }

      
        if ($e instanceof NotFoundHttpException) {
            return ApiResponse::error('API endpoint not found', 404);
        }

        
        if ($e instanceof UnauthorizedHttpException) {
            return ApiResponse::error('Unauthorized access', 401);
        }

        if ($e instanceof AccessDeniedHttpException) {
            return ApiResponse::error('Access denied', 403);
        }

        if ($e instanceof \App\Exceptions\BusinessException) {
            return ApiResponse::error($e->getMessage(), $e->getCode() ?: 409);
        }
        return ApiResponse::error(
            config('app.debug') ? $e->getMessage() : 'Internal server error',
            500
        );
    }

    return parent::render($request, $e); 
}

}

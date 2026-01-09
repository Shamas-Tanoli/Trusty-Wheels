<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Support\Facades\Log;


class Handler extends ExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function render($request, Throwable $exception)
    {
        // JSON request ke liye JSON response return kare
        if ($request->expectsJson()) {
            $response = [
                'success' => false,
                'message' => $this->getErrorMessage($exception),
            ];

            // ValidationException ke case me errors bhi bhejna hain
            if ($exception instanceof ValidationException) {
                $response['errors'] = $exception->errors();
            }

            if (config('app.debug')) {
                $response['class'] = get_class($exception);
                $response['trace'] = array_slice($exception->getTrace(), 0, 5); // Sirf pehle 5 trace elements
            }

            return response()->json($response, $this->getStatusCode($exception));
        }

        $this->logException($request, $exception);
        return parent::render($request, $exception);

    }

    /**
     * Exception ka readable error message return kare
     */
    protected function getErrorMessage(Throwable $exception)
    {
        return match (true) {
            $exception instanceof ModelNotFoundException => 'Record not found.',
            $exception instanceof QueryException => $exception->getCode() == "23000"
                ? 'Please remove the related records before deleting this '
                : 'Database error occurred.',
            $exception instanceof NotFoundHttpException => 'Page not found.',
            $exception instanceof ValidationException => 'Validation failed.',
            $exception instanceof HttpException => $exception->getMessage() ?: 'HTTP error occurred.',
            $exception instanceof AuthenticationException => 'Authentication failed. Please log in.',
            $exception instanceof AuthorizationException => 'You do not have permission to perform this action.',
            $exception instanceof MethodNotAllowedHttpException => 'This method is not allowed for the requested route.',
            default => 'Something went wrong.',
        };
    }

    /**
     * Exception ka HTTP status code return kare
     */
    protected function getStatusCode(Throwable $exception)
    {
        return match (true) {
            $exception instanceof ModelNotFoundException,
            $exception instanceof NotFoundHttpException => 404,
            $exception instanceof QueryException => $exception->getCode() == "23000" ? 400 : 500,
            $exception instanceof ValidationException => 422,
            $exception instanceof HttpException => $exception->getStatusCode(),
            $exception instanceof AuthenticationException => 401,
            $exception instanceof AuthorizationException => 403,
            $exception instanceof MethodNotAllowedHttpException => 405,
            default => 500,
        };
    }

    /**
     * Log the exception with request details.
     */
    protected function logException($request, Throwable $exception): void
    {
        $logLevel = match ($this->getStatusCode($exception)) {
            400, 401, 403, 404, 422 => 'warning',
            500, 503 => 'error',
            default => 'critical'
        };

        Log::$logLevel($exception->getMessage(), [
            'url' => $request->fullUrl(),          // Full URL
            'method' => $request->method(),        // HTTP Method
            'user_id' => optional($request->user())->id, // Authenticated user ID 
            'user_ip' => $request->ip(),                // User IP
            'user_agent' => $request->header('user-agent'), // User agent (browser/device info)
            'input' => $request->except(['password', 'token']), // Hide sensitive fields
            'timestamp' => now()->toDateTimeString(), // Current timestamp
            'trace' => config('app.debug') ? substr($exception->getTraceAsString(), 0, 600) : null,
        ]);
    }
}

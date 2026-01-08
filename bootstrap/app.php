<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\AuthMiddleware;
use App\Http\Middleware\RoleMiddleware;

use App\Http\Middleware\EnsureDriverIsActive;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__.'/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->web();
    $middleware->alias([
  
    'role' => RoleMiddleware::class,
    'auth.check' => AuthMiddleware::class,
    'driver.active' => EnsureDriverIsActive::class,
  ]);
   
  })
  ->withExceptions(function (Exceptions $exceptions) {
    //
  })->create();

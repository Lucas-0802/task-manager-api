<?php

use App\Exceptions\TaskOperationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware): void {
    //
  })
  ->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
      if ($request->is('api/*')) {
        return response()->json([
          'message' => $e->getMessage()
        ], 404);
      }
    });

    $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException $e, $request) {
      if ($request->is('api/*')) {
        return response()->json([
          'message' => "Method not allowed"
        ], 405);
      }
    });

     $exceptions->render(function (\Illuminate\Validation\ValidationException $e, $request) {
      if ($request->is('api/*')) {
        return response()->json([
          'message' => "Validation failed",
          'errors' => $e->errors()
        ], 422);
      }
    });
    

    $exceptions->render(function (\Throwable $th, $request) {
        return response()->json([
          'message' => "An internal server error occurred - {$th->getMessage()}"
        ], 500);
    });
  })->create();

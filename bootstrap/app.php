<?php

use App\Helpers\ResponseHelper;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Exception $e) {
          /*  if (request()->is('api/*')) {
                $statusCode = Response::HTTP_BAD_REQUEST;
                $message = $e->getMessage();

                if ($e instanceof ValidationException) {

                    $explodedMessage = explode('(', $message)[0] ?? 'Verifique sus datos';
                    $message = trim($explodedMessage);
                    $statusCode = Response::HTTP_BAD_REQUEST;

                } else if ($e instanceof NotFoundHttpException) {

                    $statusCode = Response::HTTP_NOT_FOUND;

                } else if ($e instanceof AuthorizationException || $e instanceof AuthenticationException) {

                    $statusCode = Response::HTTP_UNAUTHORIZED;
                }
                return ResponseHelper::response(ResponseHelper::fail($message, $statusCode));
            }*/
        });
    })->create();

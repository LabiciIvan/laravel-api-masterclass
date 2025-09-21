<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

$apiFile    = 'api_v1.php';
$apiPrefix  = 'api/v1';

$exceptionMappings = [
    Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException::class => 'handleAccessDeniedHttpException',
    Illuminate\Validation\ValidationException::class => 'handleValidationException',
    Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException::class => 'handleMethodNotAllowedHttpException',
];

function handleDefaultErrorResponse(): JsonResponse {
    return response()->json([
        'status'    => config('responses.http.status.error'),
        'message'   => config('responses.http.messages.error'),
        'code'      => config('responses.http.codes.error'),
    ], config('responses.http.codes.error'));
}

function handleAccessDeniedHttpException(AccessDeniedHttpException $exception): JsonResponse {
    return response()->json([
        'message'   => $exception->getMessage(),
        'status'    => config('responses.http.status.fail'),
        'codes'     => config('responses.http.codes.forbidden'),
    ], config('responses.http.codes.forbidden'));
}

function handleValidationException(ValidationException $exception): JsonResponse {
    return response()->json([
        'message'   => $exception->getMessage(),
        'status'    => config('responses.http.status.fail'),
        'codes'     => config('responses.http.codes.validation_error'),
    ], config('responses.http.codes.validation_error'));
}
function handleMethodNotAllowedHttpException(MethodNotAllowedHttpException $exception): JsonResponse {
    Log::debug('Error using a HTTP method which is not allowed: {EXCEPTION} | {EXCEPTION_MESSAGE}', ['EXCEPTION' => $exception, 'EXCEPTION_MESSAGE' => $exception->getMessage()]);
    return handleDefaultErrorResponse();
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/'.$apiFile,
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: $apiPrefix
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
        ]);

        $middleware->statefulApi();
    })
    ->withExceptions(function (Exceptions $exceptions) use ($exceptionMappings) : void {

        $exceptions->render(function (Throwable $e) use ($exceptionMappings) {

            if (!isset($exceptionMappings[get_class($e)])) {
                Log::debug('Uncaught thrown exception in bootstrap/app.php => {Exception}', ['Exception' => $e]);
                return handleDefaultErrorResponse();
            }

            return $exceptionMappings[get_class($e)]($e);
        });
    })->create();

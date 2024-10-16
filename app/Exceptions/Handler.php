<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\JWTException;

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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        $code =  $e->getCode();
        $msg  =  $e->getMessage();

        if ($e instanceof UnauthorizedException) {
            $code =  403;
        } else if ($e instanceof ValidationException) {
            $msg = $e->validator->errors()->first();
            $code = 400;
        } else if ($e instanceof NotFoundHttpException) {
            $code = 404;
            $msg = 'Route not found';
        } else if ($e instanceof AuthenticationException) {
            $code = 403;
            $msg = 'UnAuthenticated';
        } else if ($e instanceof JWTException) {
            $code = 500;
            $msg = 'Token is not provided';
        }

        if (!$code || $code > 599 || $code <= 0 || gettype($code) !== "integer") {
            $code = 500;
        }

        return response()->json([
            'status' => 'Error',
            'message' => $msg,
            'model' => NULL,
            'returnedCode' => $code
        ], $code);
    }
}

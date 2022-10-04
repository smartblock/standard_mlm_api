<?php

namespace App\Exceptions;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Laravel\Sanctum\Exceptions\MissingAbilityException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

use App\Traits\ResponseAPI;

class Handler extends ExceptionHandler
{
    use ResponseAPI;

    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->is('api/*')) {
                return $this->error('invalid_user_credential', 401);
            }
        });

        $this->renderable(function (AccessDeniedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return $this->error('permission_denied', 403);
            }
        });

        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return $this->error('invalid_endpoint', 404);
            }
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->is('api/*')) {
                return $this->error('method_not_allow', 405);
            }
        });

        $this->renderable(function (MissingAbilityException $e, $request) {
            if ($request->is('api/*')) {
                return $this->error('missing_ability', 406);
            }
        });

        $this->renderable(function (BindingResolutionException $e, $request) {
            if ($request->is('api/*')) {
                return $this->error($e, 500);
            }
        });

        $this->renderable(function (QueryException $e, $request) {
            if ($request->is('api/*')) {
                return $this->error($e, 500);
            }
        });

        $this->renderable(function (\ArgumentCountError $e, $request) {
            if ($request->is('api/*')) {
                return $this->error($e, 422);
            }
        });

        $this->renderable(function (\Illuminate\Validation\ValidationException $e, $request) {
            if ($request->is('api/*')) {
                $messages = $e->validator->errors();
                $messages_arry = "";

                foreach ($messages->messages() as $key => $value) {
                    foreach ($value as $aKey => $aValue) {
                        $messages_arry .= $aValue;
                    }
                }
                return $this->errorWithoutTranslation($messages_arry, 422);
            }
        });

        $this->reportable(function (Throwable $e) {
            return $this->error('internal_server_error', 500);
        });
    }
}

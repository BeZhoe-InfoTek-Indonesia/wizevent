<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Throwable;

class Handler extends ExceptionHandler
{
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Handle rate limiting exceptions with custom messages
        if ($exception instanceof \Illuminate\Http\Exceptions\ThrottleRequestsException) {
            $message = $this->getRateLimitMessage($request);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => $message,
                    'errors' => [
                        'email' => [$message],
                    ],
                ], 429);
            }

            // For web requests, redirect back with error message
            return redirect()
                ->back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => $message,
                ]);
        }

        return parent::render($request, $exception);
    }

    /**
     * Get custom rate limit message based on the route.
     */
    protected function getRateLimitMessage(Request $request): string
    {
        $routeName = $request->route()?->getName();

        return match ($routeName) {
            'login' => 'Too many login attempts. Please try again in :seconds seconds.',
            'register' => 'Too many registration attempts. Please try again in :seconds seconds.',
            'password.request' => 'Too many password reset requests. Please try again in :seconds seconds.',
            'password.reset' => 'Too many password reset attempts. Please try again in :seconds seconds.',
            'verification.verify' => 'Too many verification attempts. Please try again in :seconds seconds.',
            default => 'Too many requests. Please try again in :seconds seconds.',
        };
    }
}

<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ErrorLoggerService
{
    /**
     * Log detailed error information for developers
     */
    public static function logErrorPage(Request $request, int $statusCode, string $errorType = 'Unknown'): void
    {
        $errorData = [
            'status_code' => $statusCode,
            'error_type' => $errorType,
            'timestamp' => now()->toISOString(),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'route' => $request->route() ? $request->route()->getName() : null,
            'middleware' => $request->route() ? $request->route()->middleware() : [],
            'session_id' => session()->getId(),
            'environment' => app()->environment(),
        ];

        // Add user information if authenticated
        if (auth()->check()) {
            $user = auth()->user();
            $errorData['user'] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ? $user->role->label() : null,
            ];
        } else {
            $errorData['user'] = null;
        }

        // Add request data
        $errorData['request_data'] = [
            'headers' => $request->headers->all(),
            'query_params' => $request->query->all(),
            'post_data' => $request->method() === 'POST' ? $request->post() : null,
        ];

        // Log to Laravel log
        Log::warning("{$statusCode} Error - {$errorType}", $errorData);

        // Send email notification for critical errors (only in production)
        if (app()->environment('production') && in_array($statusCode, [403, 404])) {
            self::sendErrorNotification($errorData);
        }
    }

    /**
     * Send email notification for error pages
     */
    private static function sendErrorNotification(array $errorData): void
    {
        try {
            $subject = "{$errorData['status_code']} Error - " . ($errorData['user'] ? $errorData['user']['name'] : 'Guest User');

            $body = "Error Details:\n\n" .
                   "Status Code: {$errorData['status_code']}\n" .
                   "Error Type: {$errorData['error_type']}\n" .
                   "Timestamp: {$errorData['timestamp']}\n" .
                   "URL: {$errorData['url']}\n" .
                   "Method: {$errorData['method']}\n" .
                   "IP: {$errorData['ip']}\n" .
                   "User Agent: {$errorData['user_agent']}\n" .
                   "Referer: " . ($errorData['referer'] ?? 'None') . "\n" .
                   "Route: " . ($errorData['route'] ?? 'Unknown') . "\n" .
                   "Environment: {$errorData['environment']}\n";

            if ($errorData['user']) {
                $body .= "\nUser Information:\n" .
                        "ID: {$errorData['user']['id']}\n" .
                        "Name: {$errorData['user']['name']}\n" .
                        "Email: {$errorData['user']['email']}\n" .
                        "Role: " . ($errorData['user']['role'] ?? 'None') . "\n";
            } else {
                $body .= "\nUser: Not authenticated\n";
            }

            // You can uncomment this to send actual emails
            // Mail::raw($body, function ($message) use ($subject) {
            //     $message->to('developer@dundarc.com.tr')
            //             ->subject($subject);
            // });

        } catch (\Exception $e) {
            Log::error('Failed to send error notification email', [
                'error' => $e->getMessage(),
                'original_error' => $errorData
            ]);
        }
    }

    /**
     * Get formatted error context for display
     */
    public static function getErrorContext(Request $request, int $statusCode): array
    {
        return [
            'status_code' => $statusCode,
            'timestamp' => now()->format('d.m.Y H:i:s'),
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user' => auth()->check() ? [
                'name' => auth()->user()->name,
                'id' => auth()->id(),
                'role' => auth()->user()->role ? auth()->user()->role->label() : 'Rol Yok'
            ] : null,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer') ?? 'Yok',
            'route' => Route::currentRouteName() ?? 'Bilinmiyor',
            'middleware' => Route::current() ? implode(', ', Route::current()->middleware()) : 'Yok',
            'session_id' => session()->getId(),
            'environment' => app()->environment(),
        ];
    }
}
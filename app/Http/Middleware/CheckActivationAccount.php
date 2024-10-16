<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckActivationAccount extends Middleware
{
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        if (Auth::guard('user')->check()) {
            $user = Auth::guard('user')->user();
            $status = $user->status;
            if (!$status) {
                throw new Exception(__('messages.activationAccountError'), 403);
            }
        }

        if (Auth::guard('visitor')->check()) {
            $visitor = Auth::guard('visitor')->user();
            $status = $visitor->status;
            if (!$status) {
                throw new Exception(__('messages.activationAccountError'), 403);
            }
        }
        return $next($request);
    }
}

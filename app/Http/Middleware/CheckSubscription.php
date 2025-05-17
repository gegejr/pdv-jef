<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $vencimento = $user->subscription_due_date;

        if (
            !$user->subscription_active ||
            ($vencimento && now()->greaterThan($vencimento)) ||
            (now()->day > 10 && $vencimento && now()->greaterThan($vencimento))
        ) {
            return response()->view('subscription.blocked');
        }

        return $next($request);
    }
}


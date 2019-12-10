<?php

namespace App\Http\Middleware;

use Closure;

class CheckTokenType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($request->tokenType == 'alipay') {
            return redirect()->route('AliToken', $request);
        }
        if ($request->tokenType =='wxpay') {
            return redirect('/');
        }
        return $next($request);
    }
}

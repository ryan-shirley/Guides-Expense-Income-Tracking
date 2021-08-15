<?php

namespace App\Http\Middleware;

use Closure;
use Sentry\State\Scope;

class SentryContext
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
        if (auth()->check() && app()->bound('sentry')) {
            \Sentry\configureScope(function (Scope $scope): void {
                $scope->setUser([
                    'id' => auth()->user()->id,
                    'username' => auth()->user()->name,
                    // since email is sensitive information, be careful to handle it.
                    //'email' => auth()->user()->email,
                ]); 
            }); 
        }   
        
        return $next($request);
    }
}

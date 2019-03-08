<?php

namespace App\Http\Middleware\Api;

use App\Continent;
use Closure;

class ContinentTitle
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
      $continent = $request->json()->get('continent');

      $request->json()->set('continent', Continent::to_preferred_case($continent));

      return $next($request);
    }
}

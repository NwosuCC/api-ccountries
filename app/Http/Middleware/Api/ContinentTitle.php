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

      /*$continent_name = Continent::to_preferred_case(
        $request->json()->get('continent')
      );

      $continent = Continent::where('name', $continent_name)->first();
      dd($continent, 'HANDLE');

      $request->json()->set('continent', $continent ? $continent->id : $continent_name);*/

      return $next($request);
    }
}

<?php
namespace App\Http\Middleware;

use Closure;

/**
 * Class RememberUrl
 *
 * @package App\Http\Middleware
 */
class RememberUrl
{
    /**
     * Remember url, where route is config('var.rememberUrlOnRoute')
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $urls = config('var.rememberUrlOnRoute');
        if (! empty($urls)) {
            foreach ($urls as $route) {
                if ($request->is($route)) {
                    $request->session()
                        ->put('previousUrl', $request->url());
                    break;
                }
            }
        }

        return $next($request);
    }
}

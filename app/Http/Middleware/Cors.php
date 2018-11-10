<?php
/**
 * Created by PhpStorm.
 * User: PC
 * Date: 9/10/2018
 * Time: 3:24 PM
 */

namespace App\Http\Middleware;

use Closure;

class Cors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request)
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
            ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With, x-token, Lang, Client')
            ->header('Access-Control-Allow-Credentials', ' true');
    }
}

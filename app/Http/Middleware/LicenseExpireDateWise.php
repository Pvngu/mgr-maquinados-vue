<?php

namespace App\Http\Middleware;

use Closure;
use Examyou\RestAPI\Exceptions\ApiException;

class LicenseExpireDateWise
{

    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}

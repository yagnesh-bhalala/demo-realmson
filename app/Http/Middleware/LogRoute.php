<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class LogRoute
{
    /**
     * Routes that should skip handle.
     *
     * @var array
     */
    // protected $except = [
    //     'api/media-upload',
    // ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        
        $pageURL = $request->getUri();
        $log = [
            'URI' => $request->getUri(),
            'METHOD' => $request->getMethod(),
            'REQUEST_BODY' => $request->all(),
            'RESPONSE' => json_decode($response->getContent()),
        ];
        // Log::info(json_encode($log));
        error_log("\n\n -------------------------------------" . date('c'). " \n" .$pageURL. " \n" . json_encode($log), 3, storage_path().'/worker/api_log-'.date('d-m-Y').'.log');

        return $response;
    }
}
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RequestLog
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $date = date('Y-m-d H:i:s');
        $pathParam = $request->route() ? $request->route()->originalParameters() : [];
        $param = $request->all() ? $request->all() : [];
        foreach ($param as $key => $val) {
            if (str_contains(strtolower($key), 'password') || str_contains(strtolower($key), 'token')) {
                unset($param[$key]);
            }
        }

        if ($request->route() && str_contains($request->route()->getActionName(), '@')) {
            [$controller, $action] = explode('@', $request->route()->getActionName());
        } else {
            [$controller, $action] = [null, null];
        }
        $controller && $controller = str_replace('App\\Http\\Controllers\\', '', $controller);
        DB::table('request_logs')->insert([
            'ip' => $request->getClientIp(),
            'user_id' => uid(),
            'param' => $param ? json_encode($param, JSON_UNESCAPED_UNICODE) : null,
            'path_param' => $pathParam ? json_encode($pathParam, JSON_UNESCAPED_UNICODE) : null,
            'method' => $request->method(),
            'path' => $request->path(),
            'status' => $response->status(),
            'controller' => $controller,
            'action' => $action,
            'created_at' => $date,
            'updated_at' => $date,
        ]);
        return $response;
    }

}

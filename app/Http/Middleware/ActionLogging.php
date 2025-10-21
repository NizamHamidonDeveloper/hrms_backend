<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\SystemLog;
use App\Enum\LogAttributes;
use App\Enum\LogType;

class ActionLogging
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if($request->attributes->get(LogAttributes::LogEnabled->value) == true){
            $action = $request->route()->getActionName();
            $controller = null;
            $method = null;
            if (strpos($action, '@') !== false) {
                [$controller, $method] = explode('@', class_basename($action));
            }

            $log_type = LogType::tryFrom($request->attributes->get(LogAttributes::LogType->value));

            $systemLog = [
                'user_id' => $request->attributes->get(LogAttributes::LogUserId->value) ?? Auth::user()?->id,
                'action' => $log_type == LogType::Success ? $request->attributes->get(LogAttributes::LogAction->value): 'Failed Action',
                'action_detail' => $log_type == LogType::Success ? null : $request->attributes->get(LogAttributes::LogAction->value),
                'url' => $request->fullUrl(),
                'controller_name' => $controller,
                'method_name' => $method,
                'log_type' => $request->attributes->get(LogAttributes::LogType->value),
                'created_by' => $request->attributes->get(LogAttributes::LogUserId->value) ?? Auth::id()
            ];

            try{
                $systemLog['get_data'] = json_encode($request->query(), JSON_THROW_ON_ERROR);
                $systemLog['post_data'] = json_encode($request->post(), JSON_THROW_ON_ERROR);    
            }
            catch(\JsonException $e){
                $systemLog['get_data'] = $e->getMessage();
                $systemLog['post_data'] = $e->getMessage();
            }

            SystemLog::create($systemLog);
        }

        return $response;
    }
}

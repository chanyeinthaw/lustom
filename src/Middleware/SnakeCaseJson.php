<?php

namespace Lumos\Lustom\Middleware;

use Closure;

class SnakeCaseJson
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
        if ($request->getContentType() === 'json') {
            $data = $request->all();
            $snakeCase = [];

            foreach($data as $key => $value) {
                $snakeCase[$this->snakeCase($key)] = $value;
                unset($request[$key]);
            }

            $request->merge($snakeCase);
        }

        return $next($request);
    }

    private function snakeCase($string) {
        return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $string));
    }
}

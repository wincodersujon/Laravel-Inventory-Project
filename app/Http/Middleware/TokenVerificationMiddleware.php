<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;

class TokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token=$request->header('token');
        $result=JWTToken::VerifyToken($token);
        if($result=='unauthorized'){
                return response()->json([
                    'status' => 'failed',
                    'message' => 'unauthorized',
                ],401);
        }
        else{
            $request->headers->set('email',$result);
            return $next($request);
        }
    }
}

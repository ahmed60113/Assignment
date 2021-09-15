<?php
namespace App\Http\Middleware;

use App\Http\Traits\ApiResponseTrait as TraitsApiResponseTrait;
use App\Traits\ApiResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\JWTAuth;
use Carbon\Exceptions\Exception;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
class AuthJWT extends BaseMiddleware
{
    use TraitsApiResponseTrait;
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) throw new Exception('User Not Found');
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException) {
                return $this->ApiResponse(400, 'Invalid Token !!');
            } else if ($e instanceof TokenExpiredException) {
                return $this->ApiResponse(400, 'Expired Token !!');
            } else {
                if ($e->getMessage() === 'User Not Found') {
                    return $this->ApiResponse(400, 'User Not Found');
                }
                return $this->ApiResponse(400, 'Authorization Token not found');
            }
        }
        define("USER_DETAILS", $user);
        return $next($request);

    }
}


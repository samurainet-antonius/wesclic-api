<?php
namespace App\Http\Middleware;

use Closure;
use Exception;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use App\Utils\FuncResponse;

class JwtMiddleware{

  use FuncResponse;

  const AUTHORIZATION = 'Authorization';
  const INFO = 'info';

  // create function handle middleware
  public function handle($request, Closure $next, $guard = null){

    $token = $request->header(self::AUTHORIZATION);


    if(!$token){
      return $this->responseUnauthorized([self::INFO => 'Token not provided']);
    }

    try{
      // token decode
      $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);

    }catch(ExpiredException $e){

      // token is expired
      return $this->responseValidation([self::INFO => 'Provided token is expired.']);

    }catch(Exception $e){

      // error decode token
      return $this->responseValidation([self::INFO => 'An error while decoding token.']);

    }

    // find User by credentials
    $user = User::find($credentials->sub);

    // Now let's put the user in the request class so that you can grab it from there
    $request->auth = $user;

    return $next($request);

  }
}

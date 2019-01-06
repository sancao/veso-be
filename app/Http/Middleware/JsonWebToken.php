<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;
use Firebase\JWT\JWT;

//use Firebase\JWT\SignatureInvalidException;
//use Firebase\JWT\ExpiredException;

class JsonWebToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token  = $request->header('x-token', '');

        // var_dump( $token);die;

        // Get URL without prefix
        $path   = str_replace($request->route()->getPrefix() .'/', '', $request->path());

        // var_dump( $path);die;

        // Create global "client" config value
        config(['quyen' => (int)$request->header('quyen')]);

        // var_dump($request->header('client'));die;

        // Check token & get user data
        $userInfo   = $this->_checkValidToken($token, $path, config('quyen'));

        // var_dump( $userInfo);die;

        // Token exception
        if (!($userInfo['status'])) {
            return response()->json($userInfo, 400);
        }

        return $next($request);
    }

    /**
     * Validate Token
     * @param $token
     * @param $requestPath
     * @param $client
     * @return array
     */
    private function _checkValidToken($token, $requestPath, $quyen)
    {

        // Initial result
        $success    = false;
        $message    = '';
        $data       = [];
        // Initial result

        try {
            $key    = config('app.jwt-key');

            $decoded = JWT::decode($token, $key, array('HS256'));
            $decoded_array = (array)$decoded;

            //Get logged data
            $context = $decoded_array['context'];

            // var_dump($quyen);die;

            // Check requestPath is valid permission & Client is valid for user
            if ($context->quyen==='admin'|| (in_array($requestPath, $context->route_list))) {
                $success = true;
                $message = 'Success';
                $data = $context;
            } else {
                $message = __('auth.no_permission');
            }
        } catch (\Exception $e) {
            report($e);
            $message    = $e->getMessage();
        }
        /*
            catch(SignatureInvalidException $e){}
            catch(ExpiredException $e){}
        */

        return [
            'status'    => $success,
            'message'   => $message,
            'data'      => $data,
        ];
    }

    //-------------------------------------------------------
    // Todo: Temporary comment to remove redis
    //-------------------------------------------------------
    /*private function _checkValidToken($token, $requestPath){

        $key    = config('app.jwt-key');

        // Initial result
        $success    = false;
        $message    = '';
        $data       = [];
        // Initial result

        // --- 1. Check redis
        $redis = Redis::connection();
        $pattern = '*' . config('app.redis-delimiter') . $token . config('app.redis-delimiter');
        $keyArray   = $redis->keys($pattern);

        if(empty($keyArray)){
            $message     = __('auth.invalid_token');
        }else{
            // --- 2. Redis OK => Check token info
            try{
                $decoded = JWT::decode($token, $key, array('HS256'));
                $decoded_array = (array) $decoded;

                //Get logged data
                $context       = $decoded_array['context'];

                // Check requestPath is valid permission
                if(in_array($requestPath, $context->route_list)){
                    $success    = true;
                    $message    = 'Success';
                    $data       = $context;
                }else{
                    $message    = __('auth.no_permission');
                }

            }catch(SignatureInvalidException $e){
                // Invalid token
                $message    = $e->getMessage();
            }catch(ExpiredException $e){
                // Expired token
                $message   = $e->getMessage();
            }
        }

        return [
            'success'   => $success,
            'message'   => $message,
            'data'      => $data,
        ];
    }*/
}

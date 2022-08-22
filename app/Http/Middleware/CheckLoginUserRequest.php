<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController as ApiController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Auth as AuthModel;

class CheckLoginUserRequest extends ApiController
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        // print_r($request->all()); die('kkk');
        $apiData = $request->all();
        
        if(!isset($apiData['data']['langType']) || empty($apiData['data']['langType'])) {
            return $this->sendResponse($this->responseMessage('passlang',1));
        }
        
        if(!isset($apiData['data']['token']) || empty($apiData['data']['token'])){
            return $this->sendResponse($this->responseMessage('tokenRequired',$apiData['data']['langType']));
        }
        
        if (!isset($apiData['data']['langType']) || empty($apiData['data']['langType'])) {
            $response['status'] = "0";
            $response['message'] = "Please enter language type";
            return $this->sendResponse($response);
        }

        if (!isset($apiData['data']['token']) || empty($apiData['data']['token'])) {
            $response['status'] = "2";
            $response['message'] = "Token required";
            return $this->sendResponse($response);
        }
        
        $authModel = new AuthModel;
        $auth = $authModel->getData(['token' => $apiData['data']['token']], true);
        if (empty($auth)) {
            $response['status'] = "2";
            $response['message'] = "Authentication failed";
            return $this->sendResponse($response);
        }

        $user = User::getData(['id' => $auth->userId], true);
        if (empty($user)) {
            $response['status'] = "2";
            $response['message'] = "Authentication failed";
            return $this->sendResponse($response);
        }
        if ($user->status == 2) {
            $response['status'] = "5";
            $response['message'] = "User blocked";
            return $this->sendResponse($response);
        }
        if ($user->status == 3) {
            $response['status'] = "2";
            $response['message'] = "User deleted";
            return $this->sendResponse($response);
        }
        if(!isset($user->timeZone) || empty($user->timeZone)){
            $user->timeZone = Config('app.timezone');
        }
        $user->token = $auth->token;
        $request->data = $apiData['data'];
        $request->authUser = $user;
        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController as ApiController;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Auth as AuthModel;

class CheckGuestUserRequest extends ApiController
{
    /**
     * Handle an incoming request.
     * 
     * This Is guest user Middleware
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        $apiData = $request->all();
        // $apiData = json_decode(file_get_contents('php://input'), TRUE);
        if(isset($apiData['langType']) && !empty($apiData['langType'])) {
            $apiData['data']['langType'] = $apiData['langType'];
        }
        if(!isset($apiData['data']['langType']) || empty($apiData['data']['langType'])) {
            
            $response['message'] = $this->responseMessage('pleaseEnterLangType', 1);
            return $this->sendResponse($response);
        }
        if(isset($apiData['data']['token']) && !empty($apiData['data']['token'])) {
            $authModel = new AuthModel;
            $getUserData = $authModel->getData(['token'=>$apiData['data']['token']], true);
            if(!$getUserData){
                $response['status'] = ApiController::FAIL_AUTH;
                $response['message'] = $this->responseMessage('failToAuth',$apiData['data']['langType']);
                return $this->sendResponse($response);
            }
            if($getUserData->status == 0) {
                $response['status'] = ApiController::VERIFY;
                $response['message'] = $this->responseMessage('verifyEmail',$apiData['data']['langType']);
                return $this->sendResponse($response);
            } else if($getUserData->status == 2) {
                $response['status'] = ApiController::BLOCKED;
                $response['message'] = $this->responseMessage('blockedAccount',$apiData['data']['langType']);
                return $this->sendResponse($response);
            }
            $request->authUser = $getUserData;
        }
        $request->data = $apiData['data'];

        return $next($request);
    }
}

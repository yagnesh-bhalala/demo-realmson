<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController as ApiController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User as User;
use App\Models\Common as CommonModel;
use App\Models\UserSocialAuth as UserSocialAuthModel;
use App\Models\Auth as AuthModel;
use App\Models\Background as BackgroundModel;
use Validator;
use Mail;
use Exception;
use Throwable;


class AuthController extends ApiController
{
    protected $commonModel;
    protected $backgroundModel;
    protected $authModel;
    protected $userSocialAuthModel;
    public function __construct() {
        $this->commonModel = new CommonModel;
        $this->backgroundModel = new BackgroundModel;
        $this->authModel = new AuthModel;
        $this->userSocialAuthModel = new UserSocialAuthModel;
    }

    public function signup(Request $request) {  
        $data = $request->data;
        if (!isset($data['firstName']) || empty($data['firstName'])) {
            return $this->sendResponse($this->responseMessage('firstNameRequired',$data['langType']));
        }

        if (!isset($data['email']) || empty($data['email'])) {
            return $this->sendResponse($this->responseMessage('emailRequired',$data['langType']));
        }
        
        if (!isset($data['password']) || empty($data['password'])) {
            return $this->sendResponse($this->responseMessage('passwordRequired',$data['langType']));
        }

        $data['role'] = 2;
        // if (!isset($data['role']) || empty($data['role'])) {
        //     return $this->sendResponse($this->responseMessage('roleRequired',$data['langType']));
        // }

        if (isset($data['email']) && !empty($data['email'])) {
            $mailExist = User::getData(['email' => strtolower($data['email'])], TRUE);
            if(!empty($mailExist)) {

                if($mailExist->status == 0) {
                    $setData['verificationCode'] = $this->commonModel->random_string(4);
                    $user = User::setData($setData,$mailExist->id);
                    if ($user) {
                        $this->backgroundModel->userSignupMail($user);
                        $this->apiResponse['status'] = "3";
                        $this->apiResponse['message'] = $this->responseMessage("verifyAccount", $data['langType']);
                        return $this->sendResponse($this->apiResponse);
                    }
                }
                $this->apiResponse['message'] = $this->responseMessage("emailExist", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }
        }
        
        $setData['firstName'] = $data['firstName'];
        if (isset($data['lastName']) && !empty($data['lastName'])) {
            $setData['lastName'] = $data['lastName'];
        }
        
        $setData['email'] = $data['email'];
        $setData['verificationCode'] = $this->commonModel->random_string(4);
        $setData['password'] = bcrypt($data['password']);
        
        $user = User::setData($setData);
        if ($user) {
            $sssss = $this->backgroundModel->userSignupMail($user);
            // var_dump($sssss);die;   
            $response['status'] = "3";
            $response['message'] = $this->responseMessage('verifyEmail',$data['langType']);
        } else {
           $response['status'] = "0";
           $response['message'] = $this->responseMessage('failSignUp',$data['langType']);
        }
        return $this->sendResponse($response);
    }

    public function resendVerification(Request $request) {
        $data = $request->all();
        if (!isset($data['data']['email']) || empty($data['data']['email'])) {
            return $this->sendResponse($this->responseMessage('emailRequired',$request['data']['langType']));
        }

        $data['data']['role'] = 2;
        // if (!isset($data['data']['role']) || empty($data['data']['role']) || !in_array($data['data']['role'], [2,3])) {
        //     return $this->sendResponse($this->responseMessage('roleRequired',$request['data']['langType']));
        // }
        
        $user = User::getData(['email'=>$data['data']['email'], 'role' => $data['data']['role'], 'status' => [0]], true);
        if (empty($user)) {
            return $this->sendResponse($this->responseMessage('userNotExist',$request['data']['langType']));
        }

        User::setData(['verificationCode' => $this->commonModel->random_string(4)], $user->id);
        $isSent = $this->backgroundModel->userVerificationMail($user->id);
        if ($isSent) {
            $this->apiResponse['status'] = "1";
            $this->apiResponse['message'] = $this->responseMessage('mailSendSuccess', $request['data']['langType']);
        } else {
            $this->apiResponse['status'] = "0";
            $this->apiResponse['message'] = $this->responseMessage('mailNotSent ', $request['data']['langType']);
        }

        return $this->sendResponse($this->apiResponse);
    }

    public function verify(Request $request) {
        $data = $request->all();
        if (!isset($data['data']['deviceType']) || empty($data['data']['deviceType'])) {
            return $this->sendResponse($this->responseMessage('deviceTypeRequired',$request['data']['langType']));
        }
        if (!isset($data['data']['email']) || empty($data['data']['email'])) {
            return $this->sendResponse($this->responseMessage('emailRequired',$request['data']['langType']));
        }

        if (!isset($data['data']['verificationCode']) || empty($data['data']['verificationCode'])) {
            return $this->sendResponse($this->responseMessage('verificationCodeRequired',$request['data']['langType']));
        }

        $data['data']['role'] = 2;
        // if (!isset($data['data']['role']) || empty($data['data']['role'])) {
        //     return $this->sendResponse($this->responseMessage('roleRequired',$request['data']['langType']));
        // }

        $user = User::getData(['email' => $data['data']['email'], 'role' => $data['data']['role'], 'status' => [0,1,2]], TRUE);
        if (empty($user)) {
            return $this->sendResponse($this->responseMessage('userNotExist',$request['data']['langType']));
        }

        if ($user->status == 2) {
            return $this->sendResponse($this->responseMessage('blockedAccount',$request['data']['langType']));
        }

        if (isset($data['data']['auth_provider']) && !empty($data['data']['auth_provider'])) {
            $getAuthProvider = $this->userSocialAuthModel->get(['auth_provider' => $data['data']['auth_provider'], 'userId' => $user->id], TRUE);
            if (empty($getAuthProvider)) {
                $response['status'] = "0";
                $response['message'] = $this->responseMessage('userNotExist',$request['data']['langType']);
                return $this->sendResponse($response);
            }
        }

        $user = User::getData(['email' => $data['data']['email'], "verificationCode" => strtolower($data['data']['verificationCode']), 'status' => [0,1,2]], true);
        if (empty($user)) {
            $response['message'] = $this->responseMessage('invalidVerificationCode',$request['data']['langType']);
            return $this->sendResponse($response);
        }

        $authData = array();
        if (isset($data['data']['deviceType']) && !empty($data['data']['deviceType'])) {
            $authData['deviceType'] = $data['data']['deviceType'];
        }
        if (isset($data['data']['deviceToken']) && !empty($data['data']['deviceToken'])) {
            $authData['deviceToken'] = $data['data']['deviceToken'];
        }
        if (isset($data['data']['deviceId']) && !empty($data['data']['deviceId'])) {
            $authData['deviceId'] = $data['data']['deviceId'];
        }
        $authData['userId'] = $user->id;
        $authData['token'] = $this->commonModel->getToken(120);
        $getAuth = $this->authModel->getData(['deviceId'=>$data['data']['deviceId'],'userId'=>$user->id], TRUE);
        
        if (!empty($getAuth)) {
            $authid = $this->authModel->setData($authData,$getAuth->id);
        } else {
            $authid = $this->authModel->setData($authData);
        }

        $userdata['verificationCode'] = '';
        $userdata['status'] = 1;
        $userdata['profileStatus'] = 1;
        if (isset($data['data']['timeZone']) && !empty($data['data']['timeZone'])) {
            $userdata['timeZone'] = $data['data']['timeZone'];
        }

        User::setData($userdata, $user->id);
        if (isset($data['data']['auth_provider']) && !empty($data['data']['auth_provider'])) {
            $this->usersocialauth->setData(['status' => '1'], $getAuthProvider->id);
        }
        
        $response['status'] = "1";
        $response['message'] = $this->responseMessage('loginSuccess',$request['data']['langType']);
        $response['data'] = User::userData($user->id, TRUE, $authid);
        
        return $this->sendResponse($response);
    }

    public function login(Request $request) {
        $data = $request->all();
        $langType = $request['data']['langType'];
        // $data['data']['role'] = 2;
        if (!isset($data['data']['email']) || empty($data['data']['email'])) {
            $this->apiResponse['message'] = $this->responseMessage('emailRequired', $langType);
            return $this->sendResponse($this-> apiResponse);
        }

        if (!isset($data['data']['password']) || empty($data['data']['password'])) {
            $this->apiResponse['message'] = $this->responseMessage('passwordRequired', $langType);
            return $this->sendResponse($this->apiResponse);
        }

        if (!isset($data['data']['deviceId']) || empty($data['data']['deviceId'])) {
            $this->apiResponse['message'] = $this->responseMessage('deviceIdRequired', $langType);
            return $this->sendResponse($this->apiResponse);
        }
        $data['data']['role'] = 2;
        // if (!isset($data['data']['role']) || empty($data['data']['role']) || !in_array($data['data']['role'], [2,3])) {
        //     $this->apiResponse['message'] = $this->responseMessage('roleRequired', $langType);
        //     return $this->sendResponse($this->apiResponse);
        // }

        $mailExist = User::getData(['email' => strtolower($data['data']['email']), 'role' => $data['data']['role'], 'status' => [0,1,2]], true);

        if (empty($mailExist)) {
            $this->apiResponse['message'] = $this->responseMessage('userNotExist', $langType);
            return $this->sendResponse($this->apiResponse);
        }
        
        $credentials = [
            'id' => $mailExist->id, 
            'email' => $mailExist->email, 
            'password' => $data['data']['password'],
            'role' => 2,
        ];

        if (!Auth::attempt($credentials)) {
            $this->apiResponse['message'] = $this->responseMessage('invalidUserPassword', $langType);
            return $this->sendResponse($this->apiResponse);
        }
        $user = User::getData([
            'id' => $mailExist->id, 
        ], true);

        if ($user->status == 0) {
            // $this->backgroundModel->userSignupMail($user->id);
            $this->apiResponse['status'] = "3";
            $this->apiResponse['message'] = $this->responseMessage('verifyAccount', $langType);
            $this->apiResponse['data']['email'] = $mailExist->email;
            return $this->sendResponse($this->apiResponse);
        } elseif ($user->status == 2) {
            $this->apiResponse['status'] = "5";
            $this->apiResponse['message'] = $this->responseMessage('blockedAccount', $langType);
            return $this->sendResponse($this->apiResponse);
        } else {
            if (isset($data['data']['timeZone']) && !empty($data['data']['timeZone'])) {
                User::setData(['timeZone' => $data['data']['timeZone']], $user->id);
            }
        }

        $authData = [];
        if (isset($data['data']['deviceType']) && !empty($data['data']['deviceType'])) {
            $authData['deviceType'] = $data['data']['deviceType'];
        }
        if (isset($data['data']['deviceToken']) && !empty($data['data']['deviceToken'])) {
            $authData['deviceToken'] = $data['data']['deviceToken'];
        }
        if (isset($data['data']['deviceId']) && !empty($data['data']['deviceId'])) {
            $authData['deviceId'] = $data['data']['deviceId'];
        }
        
        $authData['userId'] = $user->id;
        $authData['token'] = $this->commonModel->getToken(120);

        $getAuth = $this->authModel->getData(['deviceId' => $data['data']['deviceId'], 'userId' => $user->id], TRUE);
        if(!empty($getAuth)) {
            $authid = $this->authModel->setData($authData, $getAuth->id);
        } else {
            $authid = $this->authModel->setData($authData);
        }
        $this->apiResponse['status'] = "1";
        $this->apiResponse['message'] = $this->responseMessage('loginSuccess', $langType);
        $this->apiResponse['data'] = User::userData($user->id, TRUE, $authid);
        // $SSFS = $this->sendResponse($this->apiResponse);
        // print_r($request->getContent()); die;
        return $this->sendResponse($this->apiResponse);
    }

    public function logout(Request $request) {
        $user = $request->authUser;
        // print_r($user); die;
        $this->authModel->removeToken($user->token); 
        $this->apiResponse['status'] = "1";
        $this->apiResponse['message'] =$this->responseMessage('logoutSuccess', $request['data']['langType']); 
        return $this->sendResponse($this->apiResponse);
    }

    public function forgotPassword(Request $request) {
        $data = $request->data;
        $langType = $request['data']['langType'];
        
        if (!isset($data['email']) || empty($data['email'])) {
            $this->apiResponse['message'] = $this->responseMessage('emailRequired', $langType);
            return $this->sendResponse($this->apiResponse);
        }
        $data['role'] = 2;
        // if (!isset($data['role']) || empty($data['role'])) { 
        //     $this->apiResponse['message'] = $this->responseMessage('roleRequired', $langType);
        //     return $this->sendResponse($this->apiResponse);
        // }

        $user = User::getData(['email'=>$data['email'], 'role' => $data['role'], 'status' => [0,1,2]], true);
        if (empty($user)) {
            $this->apiResponse['message'] = $this->responseMessage('userNotExist', $langType);
            return $this->sendResponse($this->apiResponse);
        }

        if ($user->status == 2) {
            $this->apiResponse['message'] = $this->responseMessage('blockedAccount', $langType);
            return $this->sendResponse($this->apiResponse);
        }

        User::setData(['forgotCode' => $this->commonModel->random_string(4)], $user->id);
        $this->backgroundModel->userForgotPasswordMail($user->id);
        $this->apiResponse['status'] = "1";
        $this->apiResponse['message'] = $this->responseMessage('mailSendSuccess', $langType);
        $this->apiResponse['data']['email'] = $user->email;
        return $this->sendResponse($this->apiResponse);
    }

    public function checkForgotCode(Request $request) {
        $data = $request->data;
        $langType = $request['data']['langType'];
        
        if (!isset($data['email']) || empty($data['email'])) {
            $this->apiResponse['message'] = $this->responseMessage("emailRequired", $langType);
            return $this->sendResponse($this->apiResponse);
        }
        if (!isset($data['verificationCode']) || empty($data['verificationCode'])) {
            $this->apiResponse['message'] = $this->responseMessage("verificationCodeRequired", $langType);
            return $this->sendResponse($this->apiResponse);
        }

        $data['role'] = 2;
        // if (!isset($data['role']) || empty($data['role']) || !in_array($data['role'], [2,3])) {
        //     $this->apiResponse['message'] = $this->Common_Model->GetNotification("roleRequired", $langType);
        //     return $this->sendResponse($this->apiResponse);
        // }

        $user = User::getData(['email'=>$data['email'], 'role' => $data['role'], 'status' => [0,1,2]], true);
        if (empty($user)) {
            $this->apiResponse['status'] = "0";
            $this->apiResponse['message'] = $this->responseMessage("userNotExist", $langType);
            return $this->sendResponse($this->apiResponse);
        }

        if ($user->forgotCode != $data['verificationCode']) {
            $this->apiResponse['status'] = "0";
            $this->apiResponse['message'] = $this->responseMessage("invalidVerificationCode", $langType);
            return $this->sendResponse($this->apiResponse);
        }

        if (!User::setData(['status' => 1], $user->id)) {
            $this->apiResponse['status'] = "3";
            $this->apiResponse['message'] = $this->responseMessage("verificationFailed", $langType);
            return $this->sendResponse($this->apiResponse);
        }

        $this->apiResponse['status'] = "1";
        $this->apiResponse['message'] = $this->responseMessage("verificationSuccess", $langType);

        return $this->sendResponse($this->apiResponse);
    }

    public function resetPassword(Request $request) {
        $data = $request->data;
        $langType = $request['data']['langType'];
        
        if (!isset($data['email']) || empty($data['email'])) {
            $this->apiResponse['message'] = $this->responseMessage("emailRequired", $langType);
            return $this->sendResponse($this->apiResponse);
        }

        if (!isset($data['newPassword']) || empty($data['newPassword'])) {
            $this->apiResponse['status'] = "0";
            $this->apiResponse['message'] = $this->responseMessage("newPasswordRequired", $langType);
            return $this->sendResponse($this->apiResponse);
        }

        $data['role'] = 2;
        // if (!isset($data['role']) || empty($data['role'])) {
        //     $this->apiResponse['message'] = $this->responseMessage("roleRequired", $langType);
        //     return $this->sendResponse($this->apiResponse);
        // }
        
        $user = User::getData(['email'=>$data['email'], 'role' => $data['role'], 'status' => [0,1,2]], true);

        if (empty($user)) {
            $this->apiResponse['status'] = "0";
            $this->apiResponse['message'] = $this->responseMessage("userNotExist", $langType);
            return $this->sendResponse($this->apiResponse);
        }
        User::setData(['password' => bcrypt($data['newPassword'])], $user->id);
        $this->apiResponse['status'] = "1";
        $this->apiResponse['message'] = $this->responseMessage("passwordChangeSuccess", $langType);
        return $this->sendResponse($this->apiResponse);
    }

    public function changePassword(Request $request) {
        $data = $request->data;
        $user = $request->authUser;
        $langType = $request['data']['langType'];
        if (isset($data['oldPassword']) && !empty($data['oldPassword'])) {
            if (!empty($user->password) && (!isset($data['oldPassword']) || empty($data['oldPassword']))) {
                $this->apiResponse['status'] = "0";
                $this->apiResponse['message'] = $this->responseMessage("passwordRequired", $langType);
                return $this->sendResponse($this->apiResponse);
            }
            if (!isset($data['newPassword']) || empty($data['newPassword'])) {
                $this->apiResponse['status'] = "0";
                $this->apiResponse['message'] = $this->responseMessage("newPasswordRequired", $langType);
                return $this->sendResponse($this->apiResponse);
            }
            $oldPassword = $data['oldPassword'];
            $newPassword = $data['newPassword'];
            try {
                if ((Hash::check($oldPassword, $user->password)) == false) {
                    $this->apiResponse['message'] = $this->responseMessage("checkOldPassword", $langType);
                    return $this->sendResponse($this->apiResponse);
                } else if ((Hash::check($newPassword, $user->password)) == true) {
                    $this->apiResponse['message'] = $this->responseMessage("currentAndOldPasswordSholdNotMatch", $langType);
                    return $this->sendResponse($this->apiResponse);
                } else {
                    User::setData(['password' => bcrypt($data['newPassword'])], $user->id);
                    $this->apiResponse['status'] = "1";
                    $this->apiResponse['message'] = $this->responseMessage("passwordChangeSuccess", $langType);
                    
                    return $this->sendResponse($this->apiResponse);
                }
            } catch (Exception $ex) {
                if (isset($ex->errorInfo[2])) {
                    $msg = $ex->errorInfo[2];
                } else {
                    $msg = $ex->getMessage();
                }

                $this->apiResponse['status'] = "0";
                $this->apiResponse['message'] = $msg;
                return $this->sendResponse($this->apiResponse);
            }
        }
    }

    public function socialLogin(Request $request) {
        $data = $request->data;
        $langType = $request['data']['langType'];
        $authProviderArray = ['linkedin', 'facebook', 'google'];        
        if (!isset($data['auth_provider']) || empty($data['auth_provider'])) {
            $this->apiResponse['message'] = $this->responseMessage("authProviderRequired", $langType);
            return $this->sendResponse($this->apiResponse);
        } else {
            if (!in_array($data['auth_provider'], $authProviderArray) ) {
                $this->apiResponse['message'] = $this->responseMessage("authProviderMustBeIn", $langType) . implode(',',$authProviderArray);
                return $this->sendResponse($this->apiResponse);
            }
        }

        if (!isset($data['auth_id']) || empty($data['auth_id'])) {
            $this->apiResponse['message'] = $this->responseMessage("authIdRequired", $langType);
            return $this->sendResponse($this->apiResponse);
        }

        if (!isset($data['deviceId']) || empty($data['deviceId'])) {
            $this->apiResponse['message'] = $this->responseMessage("deviceIdRequired", $langType);
            return $this->sendResponse($this->apiResponse);
        }

        $data['role'] = 2;
        // if (!isset($data['role']) || empty($data['role']) || !in_array($data['role'], [2,3])) {
        //     $this->apiResponse['message'] = $this->responseMessage("roleRequired", $langType);
        //     return $this->sendResponse($this->apiResponse);
        // }

        if (!isset($data['isManualEmail']) || $data['isManualEmail'] == "") {
            $this->apiResponse['message'] = $this->responseMessage("isManualEmailRequired", $langType);
            return $this->sendResponse($this->apiResponse);
        }
        if (isset($data['email']) && !empty($data['email'])) {
            $userData['email'] = $data['email'];
        }
        if (isset($data['timeZone']) && !empty($data['timeZone'])) {
            $userData['timeZone'] = $data['timeZone'];
        }
        if (isset($data['deviceType']) && !empty($data['deviceType'])) {
            $authData['deviceType'] = $data['deviceType'];
        }
        if (isset($data['deviceToken']) && !empty($data['deviceToken'])) {
            $authData['deviceToken'] = $data['deviceToken'];
        }
        if (isset($data['deviceId']) && !empty($data['deviceId'])) {
            $authData['deviceId'] = $data['deviceId'];
        }

        $checkDetail = $this->userSocialAuthModel->get(['auth_provider' => $data['auth_provider'], 'auth_id' => $data['auth_id']], true);
        if (empty($checkDetail)) {
            if (isset($data['email']) && !empty($data['email'])) {
                $mailExist = User::getData(['email' => strtolower($data['email'])], TRUE);
                // print_r($mailExist);
                // die('hh1');
                if (!empty($mailExist)) {
                    // die('hh');
                    if ($mailExist->role != $data['role']) {
                        $this->apiResponse['status'] = "0";
                        $this->apiResponse['message'] = $this->responseMessage("emailexist", $langType);
                        return $this->sendResponse($this->apiResponse);
                    }
                    //Check auth account already exist
                    $socialAuthExist = $this->userSocialAuthModel->get(['userId' => $mailExist->id, 'auth_provider' => $data['auth_provider'],'status'=>[0,1]], TRUE);
                    if(!empty($socialAuthExist)){
                        $this->apiResponse['status'] = "0";
                        $this->apiResponse['message'] = $this->responseMessage("emailmissmatch", $langType);
                        return $this->sendResponse($this->apiResponse);
                    }
                    if ($data['isManualEmail'] == "0") {
                        $userData['profileStatus'] = 1;
                        $userData['status'] = '1';
                    } else {
                        $userData['verificationCode'] = $this->commonModel->random_string(4);
                    }
                    if (empty($mailExist->firstName) && isset($data['firstName']) && !empty($data['firstName'])) {
                        $userData['firstName'] = $data['firstName'];
                    }
                    if (empty($mailExist->lastName) && isset($data['lastName']) && !empty($data['lastName'])) {
                        $userData['lastName'] = $data['lastName'];
                    }
                    
                    $user = User::setData($userData, $mailExist->id);
                    if (empty($user)) {
                        $this->apiResponse['status'] = "0";
                        $this->apiResponse['message'] = $this->responseMessage("registerFailed", $langType);
                        return $this->sendResponse($this->apiResponse);
                    } else {
                        $getSocialAuth = $this->userSocialAuthModel->get(['userId' => $user, 'auth_provider' => $data['auth_provider'], 'auth_id' => $data['auth_id']], TRUE);
                        if (empty($getSocialAuth)) {
                            $setData['userId'] = $user;
                            $setData['auth_provider'] = $data['auth_provider'];
                            $setData['auth_id'] = $data['auth_id'];
                            if ($data['isManualEmail'] == "0") {
                                $setData['status'] = "1";
                            } else {
                                $setData['status'] = "0";
                            }
                            $this->userSocialAuthModel->setData($setData);
                        }
                    }
                    if ($data['isManualEmail'] == "0") {
                        $authData['userId'] = $user;
                        $authData['token'] = $this->commonModel->getToken(120);
                        $getAuth = $this->authModel->getData(['deviceId'=>$data['deviceId'],'userId'=>$user],TRUE);
                        if(!empty($getAuth)){
                            $authid = $this->authModel->setData($authData,$getAuth->id);
                        }else{
                            $authid = $this->authModel->setData($authData);
                        }
                        $this->apiResponse['status'] = "1";
                        $this->apiResponse['message'] = $this->responseMessage("loginSuccess", $langType);
                        $this->apiResponse['data'] = User::userData($user, TRUE, $authid);
                        return $this->sendResponse($this->apiResponse);
                    } else {
                        // $this->backgroundModel->userSignupMail($user);
                        $this->apiResponse['status'] = "3";
                        $this->apiResponse['message'] = $this->responseMessage("verifyAccount", $langType);
                        $this->apiResponse['data'] = ['email' => $data['email']];
                        return $this->sendResponse($this->apiResponse);
                    }
                } else {
                    // print_r($checkDetail);die('jj');
                    $userData['role'] = $data['role'];   
                    if (empty($mailExist->firstName) && isset($data['firstName']) && !empty($data['firstName'])) {
                        $userData['firstName'] = $data['firstName'];
                    }
                    if (empty($mailExist->lastName) && isset($data['lastName']) && !empty($data['lastName'])) {
                        $userData['lastName'] = $data['lastName'];
                    }
                    if ($data['isManualEmail'] == "0") {
                        $userData['status'] = '1';
                        $userData['profileStatus'] = 1;
                        $userData['token'] = $this->commonModel->getToken(120);
                    } else {
                        $userData['status'] = '0';
                        $userData['verificationCode'] = $this->commonModel->random_string(4);
                    }
                    $userData['password'] = '';
                    // print_r($userData);
                    // die('jjj');
                    $user = User::setData($userData);
                    // print_r($user); die;
                    if (empty($user)) {
                        $this->apiResponse['status'] = "0";
                        $this->apiResponse['message'] = $this->responseMessage("registerFailed", $langType);
                        return $this->sendResponse($this->apiResponse);
                    } else {
                        $getSocialAuth = $this->userSocialAuthModel->get(['userId' => $user, 'auth_provider' => $data['auth_provider'], 'auth_id' => $data['auth_id']], TRUE);
                        if (empty($getSocialAuth)) {
                            $setData['userId'] = $user;
                            $setData['auth_provider'] = $data['auth_provider'];
                            $setData['auth_id'] = $data['auth_id'];
                            if ($data['isManualEmail'] == "0") {
                                $setData['status'] = "1";
                            } else {
                                $setData['status'] = "0";
                            }
                            $this->userSocialAuthModel->setData($setData);
                        }
                    }

                    if ($data['isManualEmail'] == "0") {
                        $authData['userId'] = $user;
                        $authData['token'] = $this->commonModel->getToken(120);
                        $getAuth = $this->authModel->getData(['deviceId'=>$data['deviceId'],'userId'=>$user],TRUE);
                        if(!empty($getAuth)) {
                            $authid = $this->authModel->setData($authData,$getAuth->id);
                        } else {
                            $authid = $this->authModel->setData($authData);
                        }
                        $this->apiResponse['status'] = "1";
                        $this->apiResponse['message'] = $this->responseMessage("loginSuccess", $langType);
                        $this->apiResponse['data'] = User::userData($user, TRUE, $authid);
                        return $this->sendResponse($this->apiResponse);
                    } else {
                        // $this->backgroundModel->userSignupMail($user);
                        $this->apiResponse['status'] = "3";
                        $this->apiResponse['message'] = $this->responseMessage("verifyAccount", $langType);
                        $this->apiResponse['data'] = ['email' => $data['email']];
                        return $this->sendResponse($this->apiResponse);
                    }
                }
            } else {
                $this->apiResponse['status'] = "4";
                $this->apiResponse['message'] = $this->responseMessage("emailRequired", $langType);
                return $this->sendResponse($this->apiResponse);
            }
        } else {            
            $getuserData = User::getData(['id' => $checkDetail->userId], TRUE);
            if(empty($getuserData)){
                $this->apiResponse['status'] = "0";
                $this->apiResponse['message'] = $this->responseMessage("userNotExist", $langType);
                return $this->sendResponse($this->apiResponse);
            }
            //Check auth account already exist
            if ((isset($data['email']) && !empty($data['email'])) && $data['email'] != $getuserData->email) {
                $this->apiResponse['status'] = "0";
                $this->apiResponse['message'] = $this->responseMessage("emailmissmatch", $langType);
                return $this->sendResponse($this->apiResponse);
            }
            if ($getuserData->role != $data['role']) {
                $this->apiResponse['status'] = "0";
                $this->apiResponse['message'] = $this->responseMessage("existWithDiffRole", $langType);
                return $this->sendResponse($this->apiResponse);
            }
            if ($getuserData->status == 2) {
                $this->apiResponse['status'] = "5";
                $this->apiResponse['message'] = $this->responseMessage("blockedAccount", $langType);
                return $this->sendResponse($this->apiResponse);
            }
            if (($getuserData->status == 0 || $checkDetail->status == 0) && $data['isManualEmail'] == "1") {
                $user = User::setData(['verificationCode' => $this->commonModel->random_string(4), $getuserData->id]);
                // $this->backgroundModel->userSignupMail($user);
                $this->apiResponse['status'] = "3";
                $this->apiResponse['message'] = $this->responseMessage("verifyAccount", $langType);
                $this->apiResponse['data'] = ['email' => $getuserData->email];
                return $this->sendResponse($this->apiResponse);
            }
            
            $this->userSocialAuthModel->setData(['status' => '1'], $getuserData->id);
            $authData['userId'] = $getuserData->id;
            $authData['token'] = $this->commonModel->getToken(120);
            // die('j');
            $getAuth = $this->authModel->getData(['deviceId'=>$data['deviceId'],'userId'=>$getuserData->id],TRUE);
            if(!empty($getAuth)){
                $authid = $this->authModel->setData($authData,$getAuth->id);
            }else{
                $authid = $this->authModel->setData($authData);
            }
            $this->apiResponse['status'] = "1";
            $this->apiResponse['message'] = $this->responseMessage("loginSuccess", $langType);
            $this->apiResponse['data'] = User::userData($getuserData->id, TRUE, $authid);
            return $this->sendResponse($this->apiResponse);
            
        }

        return $this->sendResponse($this->apiResponse);
    }

    public function getUserProfile(Request $request) {
        $user = $request->authUser;
        $data = $request->data;
        $langType = $request['data']['langType'];

        try {

            $data = User::userData($user->id, false);
        
            if (!empty($data)) {
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("getUserinfoSuccess", $langType);
                $this->apiResponse['data'] = $data;
            } else {
                $this->apiResponse['status'] = "6";
                $this->apiResponse['message'] = $this->responseMessage("userInfoNotFound", $langType);
            }
            return $this->sendResponse($this->apiResponse);

        } catch (Throwable $th) {
            $this->apiResponse['catchError'] = "TH-ERROR-" .  $th->getCode();
            $this->apiResponse['message'] =  $th->getMessage();
            return $this->sendResponse($this->apiResponse);
        } catch (Exception $ex) {
            $this->apiResponse['catchError'] = "EX-ERROR-" .  $ex->getCode();
            $this->apiResponse['message'] =  $ex->getMessage();
            return $this->sendResponse($this->apiResponse);
        }

    }

    public function verifyAccount($confirm) {
        $verificationUrl = base64_decode(urldecode($confirm));
        $explodeData = explode('/',$verificationUrl);
        $email = $explodeData[1];
        $code = $explodeData[0];
        // echo "<pre>"; print_r($explodeData); die;

        $userDetails = User::getData(['profileStatus' => 7,'parentEmail'=>$email , 'parentVerificationCode'=>$code], TRUE);
        // echo "<pre>"; print_r($userDetails); die; 
        $data = [];
        if(!empty($userDetails)){
            $setData['parentVerificationCode'] = "";
            $setData['profileStatus'] = 8;
            $user = User::setData($setData, $userDetails->id);
            
            $data['isVerified'] = 1;
            $data['userData'] = $userDetails;
            $data['message'] = 'Thank You.';
        } else {
            $data['isVerified'] = 0;
            $data['message'] = 'Something went wrong.';
        }
        // die('kkk');
        return view('mail.parentVarified',['data'=>$data]);
    }

    public function userProfile(Request $request) {
        $user = $request->authUser;
        $data = $request->data;
        $langType = $request['data']['langType'];
        if (!isset($data['profileStatus'])) {
            $this->apiResponse['message'] = $this->responseMessage("profileProfileStatus", $langType);
            return $this->sendResponse($this->apiResponse);
        }

        $setData = [];
        if($data['profileStatus'] == 1 && 
        isset($data['birthdate']) && !empty($data['birthdate'])) 
        {
            $setData['birthdate'] = $data['birthdate'];
            
                $setData['profileStatus'] = 2;
                User::setData($setData, $user->id);
        }

        if($data['profileStatus'] == 2 || $data['profileStatus'] == 3 ) {
            $modal = User::getData(['id' => $user->id], true);
            if(!empty($modal->birthdate)) {
                $dob = $modal->birthdate;
                $today = date("Y-m-d");
                $diff = date_diff(date_create($dob), date_create($today));
                $age = $diff->format('%y');
            }
        }

        if($data['profileStatus'] == 2 && isset($data['gender']) && !empty($data['gender'])) {
            $setData['gender'] = $data['gender'];
            // $modal = User::getData(['id' => $user->id], true);
            // if(!empty($modal->birthdate)) {
                // $dob = $modal->birthdate;
                // $today = date("Y-m-d");
                // $diff = date_diff(date_create($dob), date_create($today));
                // $age = $diff->format('%y');
                // print_r($age); die;
                if (isset($age) && $age >= 14 && $age <= 19) {
                    $setData['profileStatus'] = 3;
                }
                if (isset($age) && $age >= 20) {
                    $setData['profileStatus'] = 5;
                }
            // }
            User::setData($setData, $user->id);
                               
        }

        if($data['profileStatus'] == 3 && 
            isset($data['isStudent'])) {
            // if($data['isStudent'] == 1) {
            //     $setData['profileStatus'] = 4; // yes
            // } else {
            //     $setData['profileStatus'] = 5; // no
            // }
            if (isset($age) && $age >= 13 && $age <= 17) {
                $setData['profileStatus'] = 4;
            }
            if (isset($age) && $age >= 18) {
                $setData['profileStatus'] = 5;
            }
            $setData['isStudent'] = $data['isStudent'];
            User::setData($setData, $user->id);
        }

        if(($data['profileStatus'] == 4 && 
            isset($data['parentName']) && !empty($data['parentName']) )
          && (isset($data['parentEmail']) && !empty($data['parentEmail']))
        ) {
            $setData['parentVerificationCode'] = $parentVerificationCode = $this->commonModel->random_string(4);
            $setData['parentName'] = $data['parentName'];
            $setData['parentEmail'] = $data['parentEmail'];
            $setData['profileStatus'] = 7;
            User::setData($setData, $user->id);
            $this->backgroundModel->userParentsVerificationMail($user->id, $parentVerificationCode);
        }
        // print_r($user); die('kkkkk');
        if($user->profileStatus == 7) {
            if(!empty($user->parentVerificationCode)) {
                $this->apiResponse['status'] = '1';
                $this->apiResponse['message'] = $this->responseMessage("parentpermission", $langType);
                return $this->sendResponse($this->apiResponse);
            }
        }

        if(
            ($data['profileStatus'] == 8 || $data['profileStatus'] == 5) && 
            (isset($data['image']) && !empty($data['image']) )
        ) {
            $setData = [];
            $setData['image'] = $data['image'];
            $setData['profileStatus'] = 6;
            User::setData($setData, $user->id);
        }
        if(
            ($data['profileStatus'] == 6) &&

            (isset($data['state']) && !empty($data['state'])) &&
            (isset($data['city']) && !empty($data['city'])) &&
            (isset($data['zipCode']) && !empty($data['zipCode']))
        ) {
            if($user->isStudent == 1 && !empty($user->parentVerificationCode)) {
                $this->apiResponse['status'] = '1';
                $this->apiResponse['message'] = $this->responseMessage("parentpermission", $langType);
                return $this->sendResponse($this->apiResponse);
            }
            $setData = [];
            $setData['state'] = $data['state'];
            $setData['city'] = $data['city'];
            $setData['zipCode'] = $data['zipCode'];
            $setData['profileStatus'] = 9;
            User::setData($setData, $user->id);
        }

        if(($data['profileStatus'] == 9) &&
            isset($data['annualIncome']) && !empty($data['annualIncome'])) {
            $setData = [];
            $setData['annualIncome'] = $data['annualIncome'];
            // Uncoment below line of profile status if bank related logic is implemented.
            // $setData['profileStatus'] = 8; // Remove below line of $setData['profileStatus'] = 10;
            $setData['profileStatus'] = 10;
            User::setData($setData, $user->id);
        }

        if(($data['profileStatus'] == 10) &&
            (isset($data['grade']) && !empty($data['grade'])) && 
            (isset($data['graduationYear']) && !empty($data['graduationYear'])) ) 
        {
            if($user->isStudent == 1 && !empty($user->parentVerificationCode)) {
                $this->apiResponse['status'] = '1';
                $this->apiResponse['message'] = $this->responseMessage("parentpermission", $langType);
                return $this->sendResponse($this->apiResponse);
            }
            $setData = [];
            $setData['grade'] = $data['grade'];
            $setData['graduationYear'] = $data['graduationYear'];
            $setData['profileStatus'] = 13;
            User::setData($setData, $user->id);
        }

        // ---------------------- new case ----------------------- //        
        $getData =  User::getData(['id'=>$user->id], true);
        if(!empty($getData->birthdate)) {
            $dob = $getData->birthdate;
            $today = date("Y-m-d");
            $diff = date_diff(date_create($dob), date_create($today));
            $age = $getData->age = $diff->format('%y');
            if(isset($age) && $age <= 13) {
                $this->apiResponse['message'] = $this->responseMessage("ageRistriction13", $langType);
                return $this->sendResponse($this->apiResponse);
            }
            if ($age <= 13) {
                $getData->ageFlag = '1';
            } else if ($age >= 14 && $age <= 17)  {
                $getData->ageFlag = '2';
            } else if ($age == 18 || $age == 19) {
                $getData->ageFlag = '3';
            } else if ($age >= 20) {
                $getData->ageFlag = '4';
            }
        } else {
            $getData->ageFlag = $getData->age = ''; 
        }
        // ---------------------- new case ----------------------- //

        // $getData =  User::getData(['id'=>$user->id], true);
        // if(!empty($getData->birthdate)) {
        //     $dob = $getData->birthdate;
        //     $today = date("Y-m-d");
        //     $diff = date_diff(date_create($dob), date_create($today));
        //     $this->apiResponse['status'] = '1';
        //     $this->apiResponse['message'] = $this->responseMessage('SaveSuccess',$langType);
            
        //     $getData->isUnder18n = $diff->format('%y') < 18 ? '1' : '';
        //     $getData->age = $diff->format('%y');
        // } else {
        //     $getData->isUnder18n = $getData->age = ''; 
        // }

        $this->apiResponse['status'] = '1';
        $this->apiResponse['message'] = $this->responseMessage('SaveSuccess',$langType);
        $this->apiResponse['data'] = $getData;
        return $this->sendResponse($this->apiResponse);
    }

    public function updateUserProfile(Request $request) {
        $user = $request->authUser;
        $data = $request->data;
        // print_r($data); die;
        $langType = $data['langType'];

        try{
            $input = [];
            if (isset($data['firstName']) && !empty($data['firstName']) ) {
                $input['firstName'] = $data['firstName'];
            }

            if (isset($data['lastName']) && !empty($data['lastName'])) {
                $input['lastName'] = $data['lastName'];
            }

            // if (isset($data['birthdate']) && !empty($data['birthdate'])) {
            //     if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $data['birthdate'])) {
            //         $this->apiResponse['message'] = $this->responseMessage('dateFormatNotMatched',$langType);
            //         return $this->sendResponse($this->apiResponse);
            //     }
            //         $input['birthdate'] = $data['birthdate'];
            // }

            if (isset($data['birthdate']) && !empty($data['birthdate'])) {
                if(date('Y-m-d', strtotime($data['birthdate'])) != date($data['birthdate'])) {
                    $this->apiResponse['message'] = $this->responseMessage('dateFormatNotMatched',$langType);
                    return $this->sendResponse($this->apiResponse);
                }
                $input['birthdate'] = $data['birthdate'];
            }

            if (isset($data['gender']) && !empty($data['gender'])) {
                if(!in_array($data['gender'], ["1","2","3"])) {
                    $this->apiResponse['message'] = $this->responseMessage('genderNotMatch',$langType);
                    return $this->sendResponse($this->apiResponse);    
                }
                $input['gender'] = $data['gender'];
            }

            if (isset($data['state']) && !empty($data['state'])) {
                $input['state'] = $data['state'];
            }

            if (isset($data['city']) && !empty($data['city'])) {
                $input['city'] = $data['city'];
            }

            if (isset($data['zipCode']) && !empty($data['zipCode'])) {
                $input['zipCode'] = $data['zipCode'];
            }

            if (isset($data['grade']) && !empty($data['grade'])) {
                $input['grade'] = $data['grade'];
            }

            if (isset($data['graduationYear']) && !empty($data['graduationYear'])) {
                $input['graduationYear'] = $data['graduationYear'];
            }

            if (isset($data['image']) && !empty($data['image'])) {
                $input['image'] = $data['image'];
            }

            User::setData($input, $user->id);
            $this->apiResponse['status'] = '1';
            $this->apiResponse['message'] = $this->responseMessage('SaveSuccess',$langType);
            $this->apiResponse['data'] = User::getData(['id'=>$user->id],true);
            return $this->sendResponse($this->apiResponse);

        } catch (Throwable $th) {
            $this->apiResponse['ThrowableError'] = "TH-ERROR-" .  $th->getCode();
            $this->apiResponse['message'] =  $th->getMessage();
            return $this->sendResponse($this->apiResponse);
        } catch (Exception $ex) {
            $error_code = $ex->getCode();
            return response()->json(["status" => "EX-ERROR-" . $error_code, "message" => $ex->getMessage()]);
        }
    }

    public function test() {
        $tst = $this->backgroundModel->inAppValidator(null);
        print_r($tst);
        die;
    }
}
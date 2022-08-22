<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Api\PlaidController as PlaidController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PlaidCreateLinkToken as PlaidCreateLinkToken;
use App\Models\PlaidTransaction as PlaidTransaction;
use App\Models\PlaidCategory as PlaidCategoryModel;
use App\Models\Expense as ExpenseModel;

use Throwable;
use Exception;
// use phpDocumentor\Reflection\PseudoTypes\True_;
use App\Models\User;

class PlaidApisController extends PlaidController
{
    protected $plaidCreateLinkTokenModel;
    protected $plaidTransactionModel;
    protected $plaidCategoryModel;
    protected $expenseModel;    

    public function __construct() {
        parent::__construct();
        $this->plaidCreateLinkTokenModel = new PlaidCreateLinkToken;
        $this->plaidTransactionModel = new PlaidTransaction;
        $this->plaidCategoryModel = new PlaidCategoryModel;
        $this->expenseModel = new ExpenseModel;
    }

    public function createLinkToken(Request $request) {
        $data = $request->all();
        $user = $request->authUser;
        try {

            $reqData = [
                'client_name' => $user->firstName.' '.$user->lastName,
                'country_codes' => $data['country_codes'],
                'language' => 'en',
                'user' => ['client_user_id' => $user->id.''],
                'products' => $data['products'],
            ];
    
            $result =  $this->commonCurlRequest('link/token/create', $reqData, false);
    
            $setLinkTokenData['expiration'] = $result->expiration;
            $setLinkTokenData['link_token'] = $result->link_token;
            $setLinkTokenData['request_id'] = $result->request_id;
            $setLinkTokenData['userId'] = $user->id;
    
            $setLinkTokenData['ids'] = $this->plaidCreateLinkTokenModel->setData($setLinkTokenData);
            $this->apiResponse['status'] = "1";
            $this->apiResponse['data'] = $setLinkTokenData;
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

    public function updateLinkToken(Request $request) {
        $data = $request->data;
        $user = $request->authUser;
        if (!isset($request['country_codes'])) {
            // error
        }

        $publicToken = null;
        // $linkModel = $this->plaidCreateLinkTokenModel->getData(['userId' => $user->id], true);
        // if (isset($linkModel->public_token)) {
        //     $publicToken = $linkModel->public_token;
        // }

        $bothToken = $this->generatePublicNAccessToken($publicToken,  $data['langType']);
        
        $reqData = [
            'client_name' => $user->firstName.' '.$user->lastName,
            'country_codes' => $request['country_codes'],
            'language' => $request['language'],
            'user' => ['client_user_id' => $user->id.''],
            'access_token' => $bothToken['access_token'],
           
        ];
        
        $result =  $this->commonCurlRequest('link/token/create', $reqData, 0);

        $updateLinkTokenData['expiration'] = $result->expiration;
        $updateLinkTokenData['link_token'] = $result->link_token;
        $updateLinkTokenData['public_token'] = $bothToken['public_token'];
        $updateLinkTokenData['access_token'] = $bothToken['access_token'];
        $updateLinkTokenData['request_id'] = $result->request_id;
        $updateLinkTokenData['updateFromUserId'] = $user->id;

        $updateLinkTokenId = $this->plaidCreateLinkTokenModel->setData($updateLinkTokenData);
        
        if (!empty($updateLinkTokenId)) {
            $this->apiResponse['status'] = "1";
            $this->apiResponse['message'] = $this->responseMessage("linkTokenUpdatedSuccess", $data['langType']); 
            // die('kkkkkkk');
            $this->apiResponse['data'] = $this->plaidCreateLinkTokenModel->getData(['id' => $updateLinkTokenId], true);
        } else {
            $this->apiResponse['status'] = "0";
            $this->apiResponse['message'] = $this->responseMessage("failToUpdatedLinkToken", $data['langType']);
        }
        return $this->sendResponse($this->apiResponse);
    }

    public function getPublicToken(Request $request) {
        $data = $request->data;
        $user = $request->authUser;
    
        $reqData = [
            'institution_id' => $request['institution_id'],
            'initial_products' => $request['initial_products'],
            // 'webhook' => $data['options']['webhook'],
        ];

        $result =  $this->commonCurlRequest('sandbox/public_token/create', $reqData, false);
        $setPublicTokenData['public_token'] = $result->public_token;
        $setPublicTokenData['updateFromUserId'] = $user->id;
        
        $publicTokenId = $this->plaidCreateLinkTokenModel->setData($setPublicTokenData);
        if (!empty($publicTokenId)) {
            $this->apiResponse['status'] = "1";
            $this->apiResponse['message'] = $this->responseMessage("publicTokenUpdatedSuccess", $data['langType']); 
            $this->apiResponse['data'] = $this->plaidCreateLinkTokenModel->getData(['id' => $publicTokenId], true);
        } else {
            $this->apiResponse['status'] = "0";
            $this->apiResponse['message'] = $this->responseMessage("failToUpdatedPublicToken", $data['langType']);
        }
        return $this->sendResponse($this->apiResponse);
        
    }

    public function getAccessToken(Request $request) {
        $data = $request->data;
        $user = $request->authUser;
        $publicToken = $this->plaidCreateLinkTokenModel->getData(['userId'=>$user->id], true);
        $reqData = [
            'public_token' => $publicToken->public_token,
        ];

        $result =  $this->commonCurlRequest('item/public_token/exchange', $reqData, false);

        $setAccessTokenData['access_token'] = $result->access_token;
        $setAccessTokenData['updateFromUserId'] = $user->id;
        
        $accessTokenId = $this->plaidCreateLinkTokenModel->setData($setAccessTokenData);
        if (!empty($accessTokenId)) {
            $this->apiResponse['status'] = "1";
            $this->apiResponse['message'] = $this->responseMessage("accessTokenUpdatedSuccess", $data['langType']); 
            $this->apiResponse['data'] = $this->plaidCreateLinkTokenModel->getData(['id' => $accessTokenId], true);
        } else {
            $this->apiResponse['status'] = "0";
            $this->apiResponse['message'] = $this->responseMessage("failToUpdatedAccessToken", $data['langType']);
        }
        return $this->sendResponse($this->apiResponse);
    }

    public function storeTransactionFromPlaidToDB() {
        $userModel = User::getData(['status' => [0,1,2]]);
        foreach($userModel as $user) {
            // if ($user->id != 10) continue;
            $accessToken = $this->plaidCreateLinkTokenModel->getData(['userId' => $user->id],true);

            if (!isset($accessToken->access_token) || empty($accessToken->access_token)) {
                $accessTokenError = ['userId' => $user->id, 'message' => 'cant access "access_token" from database.'];
                // die('kkkkkkkk');
                error_log("\n\n -------------------------------------" . date('c'). " \n storeTransactionFromPlaidToDB \n" . json_encode($accessTokenError), 3, storage_path().'/worker/transactions-log-store-to-DB-'.date('d-m-Y').'.log');
                // echo "cannot access 'access_token'";exit;
                continue;
            }
            
            $date = date('Y-m-d');
            $reqData = [
                'access_token' => $accessToken->access_token,
                'start_date' => date('Y-m-d', strtotime($date. ' -7 day')),
                'end_date' => $date
                // 'start_date' => $request['start_date'],
                // 'end_date' => $request['end_date'],
            ];            
    
            $result =  $this->commonCurlRequest('transactions/get', $reqData, false);
            if (empty($result->transactions)) {
                // echo "cannot access 'transactions'";exit;
                continue;
            }
            foreach($result->transactions as $transaction) {
                $transactionData = [
                    'userId' => $user->id,
                    'accountId' => $transaction->account_id,
                    'plaidCategoryId' => $transaction->category_id,
                    'expnsDate' => $transaction->date,
                    'transactionName' => $transaction->name,
                    'transactionId' => $transaction->transaction_id,
                    'expnsAmount' => $transaction->amount,
                    // 'payment_meta' => json_encode($transaction->payment_meta),
                ];
                $plaidCategory = $this->plaidCategoryModel->getData(['plaidCategoryId' => $transaction->category_id], true);
                if (isset($plaidCategory->categoryId)) {
                    $transactionData['mainCategoryId'] = $plaidCategory->categoryId;
                }
                // $exists = $this->plaidTransactionModel->getData(['transaction_id' => $transaction->transaction_id], false, true);
                // if (!$exists)
                    $this->expenseModel->setData($transactionData);

                    error_log("\n\n -------------------------------------" . date('c'). json_encode($transactionData), 3, storage_path().'/worker/plaid-transaction-cron-'.date('d-m-Y').'.log');
            }
        }
    }

    public function getTransactionFromDB(Request $request) {
        $data = $request->data;
        $user = $request->authUser;

        try {
            $page_number = (isset($data['page']) && $data['page'] != '') ? $data['page'] : '1';
            $limit = (isset($data['limit']) && $data['limit'] != '') ? $data['limit'] : 10;
            if (isset($data['page']) && $data['page'] == 1) {
                $offset = 0;
            } else {
                if (isset($data['page']) && $data['page'] != '1') {
                    $offset = ($page_number * $limit) - $limit;
                } else {
                    $offset = 0;
                }
            }

            $getTransaction = ['userId' => $user->id, 'status' => [0, 1]];
            $transactionData = [];
            // print_r(array_merge($getTransaction, ['limit' => $limit, 'offset' => $offset])); die;
            $transactionData = $this->expenseModel->getData(array_merge($getTransaction, ['limit' => $limit, 'offset' => $offset]));
            $totalData = $this->expenseModel->getData($getTransaction, false, true);

            // print_r($transactionData); die;
            if (!empty($transactionData)) {
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("transactionDataGetSuccess", $data['langType']);
                $this->apiResponse['totalPages'] = ceil($totalData / $limit) . "";
                $this->apiResponse['data'] = $transactionData;
            } else {
                $this->apiResponse['status'] = "0";
                $this->apiResponse['message'] = $this->responseMessage("transactionDataGetFail", $data['langType']);
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

    public function storePlaidDetails(Request $request){
        $user = $request->authUser;
        $data = $request->data;
        $langType = $data['langType'];
        if (
            (!isset($data['expiration']) || empty($data['expiration'])) ||
            (!isset($data['link_token']) || empty($data['link_token'])) ||
            (!isset($data['public_token']) || empty($data['public_token'])) ||
            (!isset($data['access_token']) || empty($data['access_token'])) ||
            (!isset($data['request_id']) || empty($data['request_id']))
            
        ) {
            return $this->sendResponse("Expiration,link_token and request_id are required!!");
        }

        $saveData = [
            'user_id' => $user->id,
            'expiration' => $data['expiration'],
            'link_token' => $data['link_token'],
            'public_token' => $data['public_token'],
            'access_token' => $data['access_token'],
            'request_id' => $data['request_id'],
        ];
        // print_r($saveData); die;
        $saveData['ids'] = $this->plaidCreateLinkTokenModel->setData($saveData);
        $this->apiResponse['status'] = "1";
        $this->apiResponse['data'] = $saveData;
        return $this->sendResponse($this->apiResponse);
    }

    public function createLinkTokenFirstTime(Request $request) {
        $user = $request->authUser;
        $data = $request->data;
        $langType = $data['langType'];
        if (
            (!isset($data['expiration']) || empty($data['expiration'])) ||
            (!isset($data['link_token']) || empty($data['link_token'])) ||
            (!isset($data['request_id']) || empty($data['request_id']))
            
        ) {
            return $this->sendResponse("Expiration,link_token and request_id are required!!");
        }
        $bothToken = $this->generatePublicNAccessToken(null, $langType);
        $saveData = [
            'expiration' => $data['expiration'],
            'link_token' => $data['link_token'],
            'request_id' => $data['request_id'],
            'user_id' => $user->id,
            'public_token' => $bothToken['public_token'],
            'access_token' => $bothToken['access_token'],
        ];
        // print_r($saveData); die;
        $saveData['ids'] = $this->plaidCreateLinkTokenModel->setData($saveData);
        $this->apiResponse['status'] = "1";
        $this->apiResponse['data'] = $saveData;
        return $this->sendResponse($this->apiResponse);    
    }

    public function generatePublicNAccessToken($publicToken = null, $langType) {
        // $data = $request->data;
        if ($publicToken == null) {
            /**
             * Generate Public token
             */
            $reqData = [
                'institution_id' => 'ins_3',
                'initial_products' => ['auth'],
                // 'webhook' => $data['options']['webhook'],
            ];
            $resultP =  $this->commonCurlRequest('sandbox/public_token/create', $reqData, false);
            if (!isset($resultP->public_token)) {
                $this->apiResponse['status'] = "0";
                $this->apiResponse['message'] = $this->responseMessage("publicTokenNotGenerated", $langType);
            }
            $publicToken = $resultP->public_token;
        }

        /**
         * Generate Access token
         */
        $resultA =  $this->commonCurlRequest('item/public_token/exchange', [
            'public_token' => $publicToken,
        ], false);
        // print_r($resultA); die;
        
        if (!$resultA->access_token) {
            $this->apiResponse['status'] = "0";
            $this->apiResponse['message'] = $this->responseMessage("accessTokenNotGenerated", $langType);
        }

        return ['access_token' => $resultA->access_token, 'public_token' => $publicToken];

    }

    public function storeIncomeFromPlaidToDB(Request $request){
        $user = $request->authUser;
        // print_r($user); die;
        $accessToken = $this->plaidCreateLinkTokenModel->getData(['userId' => $user->id],true);
        // print_r($accessToken); die;

        if (!isset($accessToken->access_token) || empty($accessToken->access_token)) {
            $accessTokenError = ['userId' => $user->id, 'message' => 'cant access "access_token" from database.'];
            // die('kkkkkkkk');
            error_log("\n\n --------------------------------------" . date('c'). " \n storeIncomeFromPlaidToDB \n" . json_encode($accessTokenError), 3, storage_path().'/worker/income-log-store-to-DB-'.date('d-m-Y').'.log');
            echo "cannot access 'access_token'";exit;
            
        }

        $reqData = [
            'access_token' => $accessToken->access_token,
        ];
        $result =  $this->commonCurlRequest('income/verification/paystubs/get', $reqData, false);

        print_r($result); die;
    }
    
}

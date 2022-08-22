<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;
use Exception;
use App\Models\PlaidCategory as PlaidCategoryModel;
use App\Models\Payee as PayeeModel;
use App\Models\Common as CommonModel;

class PayeeController extends ApiController
{
    protected $payeeModel;
    protected $commonModel;

    function __construct() {
        $this->payeeModel = new PayeeModel;
        $this->commonModel = new CommonModel;
    }

    public function setPayee(Request $request) {
        $user = $request->authUser;
        $data = $request->data;

        try {
            $payeeData = [];
            if (isset($data['payeeId']) && !empty($data['payeeId'])) {
                $payeeModal = $this->payeeModel->getData(['userId' => $user->id, 'id' => $data['payeeId'], 'status' => 1], TRUE);
                if (empty($payeeModal)) {
                    $this->apiResponse['message'] = $this->responseMessage("payeeDataNotFound", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
            }
            if (!isset($data['mainCategoryId']) || empty($data['mainCategoryId'])) {
                $this->apiResponse['message'] = $this->responseMessage("mainCategoryIdRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if (!isset($data['payeeName']) || empty($data['payeeName'])) {
                $this->apiResponse['message'] = $this->responseMessage("payeeNameRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if (!isset($data['payeeType']) ) {
                $this->apiResponse['message'] = $this->responseMessage("payeeTypeRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if(!in_array($data['payeeType'],[0,1,2])) {
                $this->apiResponse['message'] = $this->responseMessage("invalidPayeeType", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if($data['payeeType'] == 0 || $data['payeeType'] == 1 )  {
                if(!isset($data['recurringDateDays'])) {
                    $this->apiResponse['message'] = $this->responseMessage("recurringDateDaysRequired", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
    
                if ($data['payeeType'] == 0 && ($data['recurringDateDays'] < 1 ||  $data['recurringDateDays'] > 31)) {
                    $this->apiResponse['message'] = $this->responseMessage("recurringMDateDaysInvalid", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
                if($data['payeeType'] == 1 &&  !in_array($data['recurringDateDays'],[0,1,2,3,4,5,6])) {
                    $this->apiResponse['message'] = $this->responseMessage("recurringDateDaysInvalid", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
                $payeeData['recurringDateDays'] = $data['recurringDateDays'];
               
            } else if ($data['payeeType'] == 2) { // one time
                if (!isset($data['payeeDate']) || empty($data['payeeDate'])) {
                    $this->apiResponse['message'] = $this->responseMessage("payeeDateRequired", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }

                $payeeData['payeeDate'] = $data['payeeDate'];
            }

            if (!isset($data['payeeAmount']) || empty($data['payeeAmount'])) {
                $this->apiResponse['message'] = $this->responseMessage("payeeAmountRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if (!isset($data['autoDraft']) || empty($data['autoDraft'])) {
                $data['autoDraft'] = 0;
            }

            $payeeData['userId'] = $user->id;
            $payeeData['payeeType'] = $data['payeeType'];
            $payeeData['mainCategoryId'] = $data['mainCategoryId'];
            $payeeData['payeeName'] = $data['payeeName'];
            $payeeData['payeeAmount'] = $data['payeeAmount'];
            $payeeData['autoDraft'] = $data['autoDraft'];

            if (isset($data['payeeId']) && !empty($data['payeeId'])) {
                $payeeId = $this->payeeModel->setData($payeeData, $data['payeeId']);
            } else {
                $payeeId = $this->payeeModel->setData($payeeData);
            }

            if (!empty($payeeId)) {
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("payeeSavedSuccess", $data['langType']);
                $this->apiResponse['data'] = $this->payeeModel->getData(['userId' => $user->id, 'id' => $payeeId, 'status' => [0, 1]], TRUE);
            } else {
                $this->apiResponse['status'] = "0";
                $this->apiResponse['message'] = $this->responseMessage("payeeSaveFail", $data['langType']);
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

    public function getPayee(Request $request) {
        // die('kkkk');
        $user = $request->authUser;
        $data = $request->data;

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

            $req = ['userId' => $user->id, 'status' => [0, 1], 'fromCron' => [0,1]];
            $payeeData = [];
            $payeeData = $this->payeeModel->getData(array_merge($req, ['limit' => $limit, 'offset' => $offset]));
            $totalData = $this->payeeModel->getData($req, false, true);

            // print_r($payeeData); die;
            if (!empty($payeeData)) {
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("payeeDataGetSuccess", $data['langType']);
                $this->apiResponse['totalPages'] = ceil($totalData / $limit) . "";
                $this->apiResponse['data'] = $payeeData;
            } else {
                $this->apiResponse['status'] = "6";
                $this->apiResponse['message'] = $this->responseMessage("payeeDataGetFail", $data['langType']);
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

    public function updatePayee(Request $request) {
        die('update function stopped, contact admin ');
        $user = $request->authUser;
        $data = $request->data;
        // print_r($data); die;
        try {

            if (!isset($data['payeeId']) || empty($data['payeeId'])) {
                $this->apiResponse['message'] = $this->responseMessage("payeeIdRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if (!isset($data['mainCategoryId'])) {
                $this->apiResponse['message'] = $this->responseMessage("mainCategoryIdRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if (!isset($data['payeeName'])) {
                $this->apiResponse['message'] = $this->responseMessage("payeeNameRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if (!isset($data['payeeType'])) {
                $this->apiResponse['message'] = $this->responseMessage("payeeTypeRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if(!in_array($data['payeeType'],[0,1,2])) {
                $this->apiResponse['message'] = $this->responseMessage("invalidPayeeType", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if($data['payeeType'] == 0 || $data['payeeType'] == 1 )  {
                if(!isset($data['recurringDateDays'])) {
                    $this->apiResponse['message'] = $this->responseMessage("recurringDateDaysRequired", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
    
                if ($data['payeeType'] == 0 && ($data['recurringDateDays'] < 1 ||  $data['recurringDateDays'] > 31)) {
                    $this->apiResponse['message'] = $this->responseMessage("recurringMDateDaysInvalid", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
                if($data['payeeType'] == 1 &&  !in_array($data['recurringDateDays'],[0,1,2,3,4,5,6])) {
                    $this->apiResponse['message'] = $this->responseMessage("recurringDateDaysInvalid", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
                $updatePayeeData['recurringDateDays'] = $data['recurringDateDays'];
               
            } else if ($data['payeeType'] == 2) { // one time
                if (!isset($data['payeeDate']) || empty($data['payeeDate'])) {
                    $this->apiResponse['message'] = $this->responseMessage("payeeDateRequired", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }

                $updatePayeeData['payeeDate'] = $data['payeeDate'];
            }
            // echo "<pre>"; print_r($data['recurringDateDays']); die;

            if (!isset($data['payeeAmount'])) {
                $this->apiResponse['message'] = $this->responseMessage("payeeAmountRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if (!isset($data['autoDraft'])) {
                $data['autoDraft'] = 0;
            }

            $updatePayeeData['payeeType'] = $data['payeeType'];
            $updatePayeeData['mainCategoryId'] = $data['mainCategoryId'];
            $updatePayeeData['payeeName'] = $data['payeeName'];
            $updatePayeeData['payeeAmount'] = $data['payeeAmount'];
            $updatePayeeData['autoDraft'] = $data['autoDraft'];

            $payeeData = [];
            $payeeData = $this->payeeModel->getData(['userId' => $user->id, 'id' => $data['payeeId'], 'status' => 1], TRUE);
            
            if (empty($payeeData)) {
                $this->apiResponse['status'] = "6";
                $this->apiResponse['message'] = $this->responseMessage("payeeDataNotFound", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            $payeeId = $this->payeeModel->setData($updatePayeeData,$data['payeeId']);
            // print_r($payeeId); die;

            if (!empty($payeeId)) {
                $payeeData = $this->payeeModel->getData(['userId' => $user->id, 'id' => $data['payeeId'], 'status' => [0, 1], /* 'formatedData' => $user->timeZone */], TRUE);
                
                $payeeData->createdDateInword = $this->commonModel->get_time_ago($payeeData->createdDate);
                $payeeData->updatedDateInword = (isset($payeeData->updatedDate))? $this->commonModel->get_time_ago($payeeData->updatedDate) :"";

                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("payeeUpdatedSuccess", $data['langType']);
                $this->apiResponse['data'] = $payeeData;
            } else {
                $this->apiResponse['status'] = "0";
                $this->apiResponse['message'] = $this->responseMessage("payeeUpdateFail", $data['langType']);
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

    public function taskSchedulingPayeeCronJob() {
        $payeeModel = new PayeeModel;
        $payeeData = $payeeModel->getData(['payeeType' => [0,1], 'status' => 1, 'fromCron' => 1]);
        // echo "<pre>"; print_r($payeeData); die('kkk');
        if(!empty($payeeData)) {
            foreach($payeeData as $recurringPayee) {
                
                if($recurringPayee->payeeType == 0 && $recurringPayee->recurringDateDays == date('d')) { //0: Recurring Monthly 1: Recurring Weekly
                    $this->inserDataInPayee($payeeModel, $recurringPayee);
                } else if($recurringPayee->payeeType == 1  && $recurringPayee->recurringDateDays == date('w')) { //w - A numeric representation of the day (0 for Sunday, 6 for Saturday)
                    $this->inserDataInPayee($payeeModel, $recurringPayee);
                }
            }
        }

    }

    function inserDataInPayee($payeeModel, $recurringPayee) {
        $request = [
            'userId' => $recurringPayee->userId,
            'mainCategoryId' => $recurringPayee->mainCategoryId,
            'payeeName' => $recurringPayee->payeeName,
            'payeeType' => $recurringPayee->payeeType,
            'recurringDateDays' => null,
            'fromCron' => 0, //0:From Cron Job 1:Manual
            'payeeDate' => null,
            'payeeAmount' => $recurringPayee->payeeAmount,
            'autoDraft' => $recurringPayee->autoDraft,
            'parentPayeeId' => $recurringPayee->id,
        ];
        $request['last_inserted_id'] = $payeeModel->setData($request);
        $request['inserted_time'] = date('d-m-Y h:i A');

        error_log("\n\n -------------------------------------" . date('c'). json_encode($request), 3, storage_path().'/worker/payee-cron-'.date('d-m-Y').'.log');
    }
}

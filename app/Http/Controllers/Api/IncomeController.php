<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;
use Exception;
use Illuminate\Support\Carbon;

use App\Models\Income as IncomeModel;
use App\Models\Common as CommonModel;

class IncomeController extends ApiController
{
    protected $incomeModel;
    protected $commonModel;

    function __construct() {
        $this->incomeModel = new IncomeModel;
        $this->commonModel = new CommonModel;
    }

    public function setIncome(Request $request) {
        $user = $request->authUser;
        $data = $request->data;

        try {
            $incomeData = [];
            if (isset($data['incomeId']) && !empty($data['incomeId'])) {
                $incomeModal = $this->incomeModel->getData(['userId' => $user->id, 'id' => $data['incomeId'], 'status' => 1], TRUE);
                if (empty($incomeModal)) {
                    $this->apiResponse['message'] = $this->responseMessage("incomeDataNotFound", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
            }
            if (!isset($data['incomeName']) || empty($data['incomeName'])) {
                $this->apiResponse['message'] = $this->responseMessage("incomeNameRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if (!isset($data['incomeType']) ) {
                $this->apiResponse['message'] = $this->responseMessage("incomeTypeRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if(!in_array($data['incomeType'],[0,1,2])) {
                $this->apiResponse['message'] = $this->responseMessage("invalidIncomeType", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if($data['incomeType'] == 0 || $data['incomeType'] == 1 )  {
                if(!isset($data['recurringDateDays'])) {
                    $this->apiResponse['message'] = $this->responseMessage("recurringDateDaysRequired", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
                
                if ($data['incomeType'] == 0 && ($data['recurringDateDays'] < 1 ||  $data['recurringDateDays'] > 30)) {
                    $this->apiResponse['message'] = $this->responseMessage("recurringMDateDaysInvalid", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
                // if ($data['incomeType'] == 1 && !preg_match('/^[0-6][0-6]*$/', $data['recurringDateDays'])) {
                if($data['incomeType'] == 1 &&  !in_array($data['recurringDateDays'],[0,1,2,3,4,5,6])) {
                    $this->apiResponse['message'] = $this->responseMessage("recurringDateDaysInvalid", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
                
                $incomeData['recurringDateDays'] = $data['recurringDateDays'];
               
            } else if ($data['incomeType'] == 2) { // one time
                if (!isset($data['incomeDate']) || empty($data['incomeDate'])) {
                    $this->apiResponse['message'] = $this->responseMessage("incomeDateRequired", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }

                $incomeData['incomeDate'] = $data['incomeDate'];
            }
            if (!isset($data['incomeAmount']) || empty($data['incomeAmount'])) {
                $this->apiResponse['message'] = $this->responseMessage("incomeAmountRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            
            $incomeData['userId'] = $user->id;
            $incomeData['incomeName'] = $data['incomeName'];
            $incomeData['incomeType'] = $data['incomeType'];
            $incomeData['incomeAmount'] = $data['incomeAmount'];
            if (isset($data['incomeId']) && !empty($data['incomeId'])) {
                $incomeId = $this->incomeModel->setData($incomeData, $data['incomeId']);
            } else {
                $incomeId = $this->incomeModel->setData($incomeData);
            }

            if (!empty($incomeId)) {
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("incomeSavedSuccess", $data['langType']);
                $this->apiResponse['data'] = $this->incomeModel->getData(['userId' => $user->id, 'id' => $incomeId, 'status' => [0, 1]], TRUE);;
            } else {
                $this->apiResponse['status'] = "0";
                $this->apiResponse['message'] = $this->responseMessage("incomeSaveFail", $data['langType']);
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

    public function getIncomeMonthly(Request $request) {
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
            $req = ['userId' => $user->id, 'status' => [0, 1], 'fromCron' => [0,1], 'incomeType' => [0,1,2],
                'dateRangeStart' => date('Y-m-01'),
                'dateRangeEnd' => date('Y-m-t')
            ];
            // $endDate = date('Y-m-d',strtotime("-1 day"));
            // $staticstart = date('Y-m-d',strtotime('last Monday'));
            // $staticfinish = date('Y-m-d',strtotime('next Sunday'));
            // print_r($staticstart); die;
            $incomeData = [];
            // \DB::connection()->enableQueryLog();
            $incomeData = $this->incomeModel->getData(array_merge($req, ['limit' => $limit, 'offset' => $offset]));

            // $queries = \DB::getQueryLog();
            // print_r($incomeData); die;
            $totalData = $this->incomeModel->getData($req, false, true);
            $totalIncome = array_sum(array_column($incomeData,'incomeAmount'));
    
            if(!empty($incomeData)) {
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("incomeDataGetSuccess", $data['langType']);
                $this->apiResponse['totalPages'] = ceil($totalData / $limit) . "";
                $this->apiResponse['totalIncomeForThisMonth'] = number_format($totalIncome,2);
                $this->apiResponse['data'] = $incomeData;
            }  else {
                $this->apiResponse['status'] = "6";
                $this->apiResponse['message'] = $this->responseMessage("incomeDataGetFail", $data['langType']);
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

    public function getIncome(Request $request) {
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
            $incomeData = [];
            $incomeData = $this->incomeModel->getData(array_merge($req, ['limit' => $limit, 'offset' => $offset]));
            // print_r($incomeData); die;
            $totalData = $this->incomeModel->getData($req, false, true);
    
            if (!empty($incomeData)) {
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("incomeDataGetSuccess", $data['langType']);
                $this->apiResponse['totalPages'] = ceil($totalData / $limit) . "";
                $this->apiResponse['data'] = $incomeData;
                // print_r($totalData); die;
            } else {
                $this->apiResponse['status'] = "6";
                $this->apiResponse['message'] = $this->responseMessage("incomeDataGetFail", $data['langType']);
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

    public function updateIncome(Request $request) {
        die('updateIncome stopped, contact admin');
        $user = $request->authUser;
        $data = $request->data;
        // print_r($data); die;
        try {

            if (!isset($data['incomeId']) || empty($data['incomeId'])) {
                $this->apiResponse['message'] = $this->responseMessage("incomeIdRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if (!isset($data['incomeName'])) {
                $this->apiResponse['message'] = $this->responseMessage("incomeNameRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if (!isset($data['incomeType']) ) {
                $this->apiResponse['message'] = $this->responseMessage("incomeTypeRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if(!in_array($data['incomeType'],[0,1,2])) {
                $this->apiResponse['message'] = $this->responseMessage("invalidIncomeType", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if($data['incomeType'] == 0 || $data['incomeType'] == 1 )  {
                if(!isset($data['recurringDateDays'])) {
                    $this->apiResponse['message'] = $this->responseMessage("recurringDateDaysRequired", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
                
                if ($data['incomeType'] == 0 && ($data['recurringDateDays'] < 1 ||  $data['recurringDateDays'] > 30)) {
                    $this->apiResponse['message'] = $this->responseMessage("recurringMDateDaysInvalid", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
                // if ($data['incomeType'] == 1 && !preg_match('/^[0-6][0-6]*$/', $data['recurringDateDays'])) {
                if($data['incomeType'] == 1 &&  !in_array($data['recurringDateDays'],[0,1,2,3,4,5,6])) {
                    $this->apiResponse['message'] = $this->responseMessage("recurringDateDaysInvalid", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
                
                $updateIncomeData['recurringDateDays'] = $data['recurringDateDays'];
               
            } else if ($data['incomeType'] == 2) { // one time
                if (!isset($data['incomeDate'])) {
                    $this->apiResponse['message'] = $this->responseMessage("incomeDateRequired", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }

                $updateIncomeData['incomeDate'] = $data['incomeDate'];
            }
            // echo "<pre>"; print_r($data['recurringDateDays']); die;
            if (!isset($data['incomeAmount'])) {
                $this->apiResponse['message'] = $this->responseMessage("incomeAmountRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            $updateIncomeData['incomeName'] = $data['incomeName'];
            $updateIncomeData['incomeType'] = $data['incomeType'];
            $updateIncomeData['incomeAmount'] = $data['incomeAmount'];

            $incomeData = [];
            $incomeData = $this->incomeModel->getData(['userId' => $user->id, 'id' => $data['incomeId'], 'status' => 1], TRUE);
            
            if (empty($incomeData)) {
                $this->apiResponse['status'] = "6";
                $this->apiResponse['message'] = $this->responseMessage("incomeDataNotFound", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            $incomeId = $this->incomeModel->setData($updateIncomeData,$data['incomeId']);
            // print_r($incomeId); die;

            if (!empty($incomeId)) {
                $incomeData = $this->incomeModel->getData(['userId' => $user->id, 'id' => $data['incomeId'], 'status' => [0, 1], /* 'formatedData' => $user->timeZone */], TRUE);
                
                $incomeData->createdDateInword = $this->commonModel->get_time_ago($incomeData->createdDate);
                $incomeData->updatedDateInword = (isset($incomeData->updatedDate))? $this->commonModel->get_time_ago($incomeData->updatedDate) :"";

                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("incomeUpdatedSuccess", $data['langType']);
                $this->apiResponse['data'] = $incomeData;
            } else {
                $this->apiResponse['status'] = "0";
                $this->apiResponse['message'] = $this->responseMessage("incomeUpdateFail", $data['langType']);
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


    public function taskSchedulingIncomeCronJob()
    {
        $incomeModel = new IncomeModel;
        $incomeData = $incomeModel->getData(['incomeType' => [0,1], 'status' => 1, 'fromCron' => 1]);
        // echo "<pre>"; print_r($incomeData); die('kkk');
        if(!empty($incomeData)) {
            foreach($incomeData as $recurringIncome) {
                
                if($recurringIncome->incomeType == 0 && $recurringIncome->recurringDateDays == date('d')) { //0: Recurring Monthly 1: Recurring Weekly
                    $this->inserDataInIncome($incomeModel, $recurringIncome);
                } else if($recurringIncome->incomeType == 1  && $recurringIncome->recurringDateDays == date('w')) { //w - A numeric representation of the day (0 for Sunday, 6 for Saturday)
                    $this->inserDataInIncome($incomeModel, $recurringIncome);
                }
            }
        }

    }

    function inserDataInIncome($incomeModel, $recurringIncome) {
        $request = [
            'userId' => $recurringIncome->userId,
            'incomeName' => $recurringIncome->incomeName,
            'incomeType' => $recurringIncome->incomeType,
            'recurringDateDays' => null,
            'fromCron' => 0, //0:From Cron Job 1:Manual
            'incomeDate' => null,
            'incomeAmount' => $recurringIncome->incomeAmount,
            'parentincomeId' => $recurringIncome->id,
        ];
        $request['last_inserted_id'] = $incomeModel->setData($request);
        $request['inserted_time'] = date('d-m-Y h:i A');

        error_log("\n\n -------------------------------------" . date('c'). json_encode($request), 3, storage_path().'/worker/income-cron-'.date('d-m-Y').'.log');
    }
}

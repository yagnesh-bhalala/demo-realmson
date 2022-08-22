<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;
use Exception;
use App\Models\Expense as ExpenseModel;
use App\Models\Common as CommonModel;

class ExpenseController extends ApiController
{
    protected $expenseModel;
    protected $commonModel;

    function __construct() {
        $this->expenseModel = new ExpenseModel;
        $this->commonModel = new CommonModel;
    }

    public function setExpense(Request $request) {
        $user = $request->authUser;
        $data = $request->data;

        try {
            $expenseData = [];
            if (isset($data['expenseId']) && !empty($data['expenseId'])) {
                $expnsModal = $this->expenseModel->getData(['userId' => $user->id, 'id' => $data['expenseId'], 'status' => 1], TRUE);            
                if (empty($expnsModal)) {
                    $this->apiResponse['message'] = $this->responseMessage("expnsDataNotFound", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
            }

            if (!isset($data['mainCategoryId']) || empty($data['mainCategoryId'])) {
                $this->apiResponse['message'] = $this->responseMessage("mainCategoryIdRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if (!isset($data['transactionName']) || empty($data['transactionName'])) {
                $this->apiResponse['message'] = $this->responseMessage("transactionNameRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if (!isset($data['expnsType']) ) {
                $this->apiResponse['message'] = $this->responseMessage("expnsTypeRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if(!in_array($data['expnsType'],[0,1,2])) {
                $this->apiResponse['message'] = $this->responseMessage("invalidExpenseType", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if($data['expnsType'] == 0 || $data['expnsType'] == 1 )  {
                if(!isset($data['recurringDateDays'])) {
                    $this->apiResponse['message'] = $this->responseMessage("recurringDateDaysRequired", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
    
                if ($data['expnsType'] == 0 && ($data['recurringDateDays'] < 1 ||  $data['recurringDateDays'] > 31)) {
                    $this->apiResponse['message'] = $this->responseMessage("recurringMDateDaysInvalid", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
                if($data['expnsType'] == 1 &&  !in_array($data['recurringDateDays'],[0,1,2,3,4,5,6])) {
                    $this->apiResponse['message'] = $this->responseMessage("recurringDateDaysInvalid", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
                $expenseData['recurringDateDays'] = $data['recurringDateDays'];
               
            } else if ($data['expnsType'] == 2) { // one time
                if (!isset($data['expnsDate']) || empty($data['expnsDate'])) {
                    $this->apiResponse['message'] = $this->responseMessage("expnsDateRequired", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }

                $expenseData['expnsDate'] = $data['expnsDate'];
            }

            if (!isset($data['expnsAmount']) || empty($data['expnsAmount'])) {
                $this->apiResponse['message'] = $this->responseMessage("expnsAmountRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }
            if (!isset($data['expnsStatus']) ) {
                $this->apiResponse['message'] = $this->responseMessage("expnsStatusRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if(!in_array($data['expnsStatus'],[0,1,2])) {
                $this->apiResponse['message'] = $this->responseMessage("invalidExpenseStatus", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            $expenseData['userId'] = $user->id;
            $expenseData['mainCategoryId'] = $data['mainCategoryId'];
            $expenseData['transactionName'] = $data['transactionName'];
            $expenseData['expnsType'] = $data['expnsType'];
            $expenseData['expnsAmount'] = $data['expnsAmount'];
            $expenseData['expnsStatus'] = $data['expnsStatus'];
            if (isset($data['expenseId']) || !empty($data['expenseId'])) {
                $expenseId = $this->expenseModel->setData($expenseData, $data['expenseId']);
            } else {
                $expenseId = $this->expenseModel->setData($expenseData);
            }

            if (!empty($expenseId)) {
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("expenseSavedSuccess", $data['langType']);
                $this->apiResponse['data'] = $this->expenseModel->getData(['userId' => $user->id, 'id' => $expenseId, 'status' => [0, 1]], TRUE);
            } else {
                $this->apiResponse['status'] = "0";
                $this->apiResponse['message'] = $this->responseMessage("expenseSaveFail", $data['langType']);
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

    public function upcomingExpense(Request $request) {
        $user = $request->authUser;
        $data = $request->data;

        try {

            if (isset($data['isExpenseDashboard']) && $data['isExpenseDashboard'] == 1) {
                $limit = 3;
                $offset = 0;
            } else {
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
            }

            // if (!isset($data['expnsReport']) || empty($data['expnsReport'])) {
            //     $this->apiResponse['message'] = $this->responseMessage("expnsReportRequired", $data['langType']);
            //     return $this->sendResponse($this->apiResponse);
            // }

            $req = [
                'userId' => $user->id, 
                'status' => [0,1], 
                'fromCron' => [0,1], 
                'expnsType' => [0,1,2],
                'expnsStatus' => [1,2],
                'dateRangeStart' => date('Y-m-d'),
                'dateRangeEnd' => date('Y-m-d',strtotime("+1 month")),
            ];

            // print_r($req); die;
            $expnsData = [];
            $expnsData = $this->expenseModel->getData(array_merge($req, ['limit' => $limit, 'offset' => $offset]));
            $totalData = $this->expenseModel->getData($req, false, true);
            $totalExpense = array_sum(array_column($expnsData,'expnsAmount'));

            if (!empty($expnsData)) {
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("expnsDataGetSuccess", $data['langType']);
                $this->apiResponse['totalPages'] = ceil($totalData / $limit) . "";
                $this->apiResponse['totalExpenseForThisMonth'] = number_format($totalExpense,2);
                $this->apiResponse['data'] = $expnsData;
            } else {
                $this->apiResponse['status'] = "6";
                $this->apiResponse['message'] = $this->responseMessage("expnsDataGetFail", $data['langType']);
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

    public function monthlyExpense(Request $request) {
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
            
            $req = [
                'userId' => $user->id, 
                'status' => [0,1], 
                'fromCron' => [0,1], 
                'expnsType' => [0,1,2],
                'expnsStatus' => [0,1,2],
                'dateRangeStart' => date('Y-m-01'),
                'dateRangeEnd' => date('Y-m-t'),
            ];
            // echo date_default_timezone_get(); die;
            // print_r($req); die;
            $expnsData = [];
            $expnsData = $this->expenseModel->getData(array_merge($req, ['limit' => $limit, 'offset' => $offset]));
            $totalData = $this->expenseModel->getData($req, false, true);
            $totalExpense = array_sum(array_column($expnsData,'expnsAmount'));
    
            // print_r($expnsData); die;
            if (!empty($expnsData)) {
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("expnsDataGetSuccess", $data['langType']);
                $this->apiResponse['totalPages'] = ceil($totalData / $limit);
                $this->apiResponse['totalExpenseForThisMonth'] = number_format($totalExpense,2);
                $this->apiResponse['data'] = $expnsData;
            } else {
                $this->apiResponse['status'] = "6";
                $this->apiResponse['message'] = $this->responseMessage("expnsDataGetFail", $data['langType']);
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

    public function spendingMoney(Request $request) {
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
            
            $req = [
                'userId' => $user->id, 
                'status' => [0,1], 
                'fromCron' => [0,1], 
                'expnsType' => [0,1,2],
                'expnsStatus' => [1,2],
                'dateRangeStart' => date('Y-m-01'),
                'dateRangeEnd' => date('Y-m-t'),
            ];

            $reqPaidExpense = [
                'userId' => $user->id, 
                'status' => [0,1], 
                'fromCron' => [0,1], 
                'expnsType' => [0,1,2],
                'expnsStatus' => 0,
                'dateRangeStart' => date('Y-m-01'),
                'dateRangeEnd' => date('Y-m-t'),
            ];
            // echo date_default_timezone_get(); die;
            // print_r($req); die;
            $spendingExpnsData = [];
            $spendingExpnsData = $this->expenseModel->getData(array_merge($req, ['limit' => $limit, 'offset' => $offset]));
            $totalData = $this->expenseModel->getData($req, false, true);
            $spendingMoney = array_sum(array_column($spendingExpnsData,'expnsAmount'));
            
            $lToClearExpnsData = [];
            $lToClearExpnsData = $this->expenseModel->getData(array_merge($reqPaidExpense, ['limit' => $limit, 'offset' => $offset]));
            $totalData = $this->expenseModel->getData($reqPaidExpense, false, true);
            $lToClearMoney = array_sum(array_column($lToClearExpnsData,'expnsAmount'));

            // print_r($spendingMoney - $lToClearMoney); die;
            if (!empty($spendingExpnsData)) {
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("expnsDataGetSuccess", $data['langType']);
                $this->apiResponse['totalPages'] = ceil($totalData / $limit) . "";
                $this->apiResponse['spendingMoneyeForThisMonth'] = number_format($spendingMoney,2);
                $this->apiResponse['leftToClearThisMonth'] = number_format($spendingMoney - $lToClearMoney,2);
                $this->apiResponse['data'] = $spendingExpnsData;
            } else {
                $this->apiResponse['status'] = "6";
                $this->apiResponse['message'] = $this->responseMessage("expnsDataGetFail", $data['langType']);
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

    public function nextPayPeriod(Request $request) {
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
            
            $req = [
                'userId' => $user->id, 
                'status' => [0, 1], 
                'fromCron' => [0,1], 
                'expnsType' => [0,1,2],
                'expnsStatus' => [1,2],
                'dateRangeStart' => date('Y-m-t'),
                'dateRangeEnd' => date('Y-m-t',strtotime("+1 month")),
            ];
            // print_r($req); die;
            $expnsData = [];
            $expnsData = $this->expenseModel->getData(array_merge($req, ['limit' => $limit, 'offset' => $offset]));
            $totalData = $this->expenseModel->getData($req, false, true);
            $totalExpense = array_sum(array_column($expnsData,'expnsAmount'));
    
            // print_r($expnsData); die;
            if (!empty($expnsData)) {
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("expnsDataGetSuccess", $data['langType']);
                $this->apiResponse['totalPages'] = ceil($totalData / $limit) . "";
                $this->apiResponse['totalExpenseForThisMonth'] = number_format($totalExpense,2);
                $this->apiResponse['data'] = $expnsData;
            } else {
                $this->apiResponse['status'] = "6";
                $this->apiResponse['message'] = $this->responseMessage("expnsDataGetFail", $data['langType']);
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

    public function getExpense(Request $request) {
        $user = $request->authUser;
        $data = $request->data;
        // print_r($data['expnsDuration']); die;
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

            if (!isset($data['expnsDuration']) || empty($data['expnsDuration'])) {
                $this->apiResponse['message'] = $this->responseMessage("expnsDurationRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if(!in_array($data['expnsDuration'],["All","Yesterday","Week","Month"])) {
                $this->apiResponse['message'] = $this->responseMessage("invalidExpenseDuration", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if($data['expnsDuration'] == "All") { // For All
                $req = ['userId' => $user->id, 'status' => [0, 1], 'fromCron' => [0,1]];
                $expnsData = [];
                $expnsData = $this->expenseModel->getData(array_merge($req, ['limit' => $limit, 'offset' => $offset]));
                $totalData = $this->expenseModel->getData($req, false, true);
        
                // print_r($expnsData); die;
                if (!empty($expnsData)) {
                    $this->apiResponse['status'] = "1";
                    $this->apiResponse['message'] = $this->responseMessage("expnsDataGetSuccess", $data['langType']);
                    $this->apiResponse['totalPages'] = ceil($totalData / $limit) . "";
                    $this->apiResponse['data'] = $expnsData;
                } else {
                    $this->apiResponse['status'] = "6";
                    $this->apiResponse['message'] = $this->responseMessage("expnsDataGetFail", $data['langType']);
                }
            } elseif($data['expnsDuration'] == "Yesterday") { // For Yesterday
                $req = [
                    'userId' => $user->id, 
                    'status' => [0, 1], 
                    'fromCron' => [0,1], 
                    'expnsType' => [0,1,2],
                    'expnsStatus' => [1,2],
                    'dateRangeStart' => date('Y-m-d',strtotime("-1 day")),
                    'dateRangeEnd' => date('Y-m-d')
                ];
                $expnsData = [];
                $expnsData = $this->expenseModel->getData(array_merge($req, ['limit' => $limit, 'offset' => $offset]));
                $totalData = $this->expenseModel->getData($req, false, true);
        
                // print_r($expnsData); die;
                if (!empty($expnsData)) {
                    $this->apiResponse['status'] = "1";
                    $this->apiResponse['message'] = $this->responseMessage("expnsDataGetSuccess", $data['langType']);
                    $this->apiResponse['totalPages'] = ceil($totalData / $limit) . "";
                    $this->apiResponse['data'] = $expnsData;
                } else {
                    $this->apiResponse['status'] = "6";
                    $this->apiResponse['message'] = $this->responseMessage("expnsDataGetFail", $data['langType']);
                }
            } elseif($data['expnsDuration'] == "Week") { // For this week
                $req = [
                    'userId' => $user->id, 
                    'status' => [0, 1], 
                    'fromCron' => [0,1], 
                    'expnsType' => [0,1,2],
                    'expnsStatus' => [1,2],
                    'dateRangeStart' => date('Y-m-d',strtotime('last Monday')),
                    'dateRangeEnd' => date('Y-m-d',strtotime('next Sunday'))
                ];
                $expnsData = [];
                $expnsData = $this->expenseModel->getData(array_merge($req, ['limit' => $limit, 'offset' => $offset]));
                $totalData = $this->expenseModel->getData($req, false, true);
        
                // print_r($expnsData); die;
                if (!empty($expnsData)) {
                    $this->apiResponse['status'] = "1";
                    $this->apiResponse['message'] = $this->responseMessage("expnsDataGetSuccess", $data['langType']);
                    $this->apiResponse['totalPages'] = ceil($totalData / $limit) . "";
                    $this->apiResponse['data'] = $expnsData;
                } else {
                    $this->apiResponse['status'] = "6";
                    $this->apiResponse['message'] = $this->responseMessage("expnsDataGetFail", $data['langType']);
                }
            } elseif($data['expnsDuration'] == "Month") { // For this month
                $req = [
                    'userId' => $user->id, 
                    'status' => [0, 1], 
                    'fromCron' => [0,1], 
                    'expnsType' => [0,1,2],
                    'expnsStatus' => [1,2],
                    'dateRangeStart' => date('Y-m-01'),
                    'dateRangeEnd' => date('Y-m-t')
                ];
                $expnsData = [];
                $expnsData = $this->expenseModel->getData(array_merge($req, ['limit' => $limit, 'offset' => $offset]));
                $totalData = $this->expenseModel->getData($req, false, true);
        
                // print_r($expnsData); die;
                if (!empty($expnsData)) {
                    $this->apiResponse['status'] = "1";
                    $this->apiResponse['message'] = $this->responseMessage("expnsDataGetSuccess", $data['langType']);
                    $this->apiResponse['totalPages'] = ceil($totalData / $limit) . "";
                    $this->apiResponse['data'] = $expnsData;
                } else {
                    $this->apiResponse['status'] = "6";
                    $this->apiResponse['message'] = $this->responseMessage("expnsDataGetFail", $data['langType']);
                }
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

    public function updateExpenseNote(Request $request) {
        $user = $request->authUser;
        $data = $request->data;
        // print_r($data); die;
        try {

            if (!isset($data['expenseId']) || empty($data['expenseId'])) {
                $this->apiResponse['message'] = $this->responseMessage("expenseIdRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }
            $data['expenseNote'] = "";
            // if (!isset($data['expenseNote']) || empty($data['expenseNote'])) {
            //     $this->apiResponse['message'] = $this->responseMessage("expenseNoteRequired", $data['langType']);
            //     return $this->sendResponse($this->apiResponse);
            // }

            if (!isset($data['expnsStatus']) ) {
                $this->apiResponse['message'] = $this->responseMessage("expnsStatusRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if(!in_array($data['expnsStatus'],[0,1,2])) {
                $this->apiResponse['message'] = $this->responseMessage("invalidExpenseStatus", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            $expnsData = [];
            $expnsData = $this->expenseModel->getData(['userId' => $user->id, 'id' => $data['expenseId'], 'status' => 1], TRUE);
            
            if (empty($expnsData)) {
                $this->apiResponse['status'] = "6";
                $this->apiResponse['message'] = $this->responseMessage("expnsDataNotFound", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            $expenseId = $this->expenseModel->setData(['expnsNote' => $data['expenseNote'], 'expnsStatus' => $data['expnsStatus']],$data['expenseId']);
            // print_r($expenseId); die;

            if (!empty($expenseId)) {
                $expnsData = $this->expenseModel->getData(['userId' => $user->id, 'id' => $data['expenseId'], 'status' => [0, 1], /* 'formatedData' => $user->timeZone */], TRUE);
                
                $expnsData->createdDateInword = $this->commonModel->get_time_ago($expnsData->createdDate);
                $expnsData->updatedDateInword = (isset($expnsData->updatedDate))? $this->commonModel->get_time_ago($expnsData->updatedDate) :"";

                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("expenseUpdatedSuccess", $data['langType']);
                $this->apiResponse['data'] = $expnsData;
            } else {
                $this->apiResponse['status'] = "0";
                $this->apiResponse['message'] = $this->responseMessage("expenseUpdateFail", $data['langType']);
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

    public function updateExpense(Request $request) {
        die('cantact admin');
        $user = $request->authUser;
        $data = $request->data;

        try {
            if (!isset($data['expenseId']) || empty($data['expenseId'])) {
                $expnsData = $this->expenseModel->getData(['userId' => $user->id, 'id' => $data['expenseId'], 'status' => 1], TRUE);            
                if (empty($expnsData)) {
                    $this->apiResponse['message'] = $this->responseMessage("expnsDataNotFound", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
            }
            $data['expenseNote'] = "";
            // if (!isset($data['expenseNote']) || empty($data['expenseNote'])) {
            //     $this->apiResponse['message'] = $this->responseMessage("expenseNoteRequired", $data['langType']);
            //     return $this->sendResponse($this->apiResponse);
            // }

            if (!isset($data['mainCategoryId'])) {
                $this->apiResponse['message'] = $this->responseMessage("mainCategoryIdRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if (!isset($data['transactionName'])) {
                $this->apiResponse['message'] = $this->responseMessage("transactionNameRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if (!isset($data['expnsType'])) {
                $this->apiResponse['message'] = $this->responseMessage("expnsTypeRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if(!in_array($data['expnsType'],[0,1,2])) {
                $this->apiResponse['message'] = $this->responseMessage("invalidExpenseType", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if($data['expnsType'] == 0 || $data['expnsType'] == 1 )  {
                if(!isset($data['recurringDateDays'])) {
                    $this->apiResponse['message'] = $this->responseMessage("recurringDateDaysRequired", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
    
                if ($data['expnsType'] == 0 && ($data['recurringDateDays'] < 1 ||  $data['recurringDateDays'] > 31)) {
                    $this->apiResponse['message'] = $this->responseMessage("recurringMDateDaysInvalid", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
                if($data['expnsType'] == 1 &&  !in_array($data['recurringDateDays'],[0,1,2,3,4,5,6])) {
                    $this->apiResponse['message'] = $this->responseMessage("recurringDateDaysInvalid", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }
                $updateExpenseData['recurringDateDays'] = $data['recurringDateDays'];
               
            } else if ($data['expnsType'] == 2) { // one time
                if (!isset($data['expnsDate']) || empty($data['expnsDate'])) {
                    $this->apiResponse['message'] = $this->responseMessage("expnsDateRequired", $data['langType']);
                    return $this->sendResponse($this->apiResponse);
                }

                $updateExpenseData['expnsDate'] = $data['expnsDate'];
            }
            // echo "<pre>"; print_r($data['recurringDateDays']); die;

            if (!isset($data['expnsAmount'])) {
                $this->apiResponse['message'] = $this->responseMessage("expnsAmountRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }
            if (!isset($data['expnsStatus'])) {
                $this->apiResponse['message'] = $this->responseMessage("expnsStatusRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            if(!in_array($data['expnsStatus'],[0,1,2])) {
                $this->apiResponse['message'] = $this->responseMessage("invalidExpenseStatus", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            $updateExpenseData['mainCategoryId'] = $data['mainCategoryId'];
            $updateExpenseData['transactionName'] = $data['transactionName'];
            $updateExpenseData['expnsType'] = $data['expnsType'];
            $updateExpenseData['expnsAmount'] = $data['expnsAmount'];
            $updateExpenseData['expnsStatus'] = $data['expnsStatus'];
            $expenseId = $this->expenseModel->setData($updateExpenseData);

            $expnsData = [];
            $expnsData = $this->expenseModel->getData(['userId' => $user->id, 'id' => $data['expenseId'], 'status' => 1], TRUE);
            
            if (empty($expnsData)) {
                $this->apiResponse['status'] = "6";
                $this->apiResponse['message'] = $this->responseMessage("expnsDataNotFound", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            $expenseId = $this->expenseModel->setData($updateExpenseData,$data['expenseId']);
            // print_r($expenseId); die;

            if (!empty($expenseId)) {
                $expnsData = $this->expenseModel->getData(['userId' => $user->id, 'id' => $data['expenseId'], 'status' => [0, 1], /* 'formatedData' => $user->timeZone */], TRUE);
                
                $expnsData->createdDateInword = $this->commonModel->get_time_ago($expnsData->createdDate);
                $expnsData->updatedDateInword = (isset($expnsData->updatedDate))? $this->commonModel->get_time_ago($expnsData->updatedDate) :"";

                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("expenseUpdatedSuccess", $data['langType']);
                $this->apiResponse['data'] = $expnsData;
            } else {
                $this->apiResponse['status'] = "0";
                $this->apiResponse['message'] = $this->responseMessage("expenseUpdateFail", $data['langType']);
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

    public function clearedBills(Request $request) {
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
            $req = [
                'userId' => $user->id, 
                'status' => [0, 1], 
                'expnsStatus' => 0, 
                'fromCron' => [0,1]
            ];
            
            // echo date_default_timezone_get(); die;
            // print_r($req); die;
            $expnsData = [];
            $expnsData = $this->expenseModel->getData(array_merge($req, ['limit' => $limit, 'offset' => $offset]));
            $totalData = $this->expenseModel->getData($req, false, true);
            $totalExpense = array_sum(array_column($expnsData,'expnsAmount'));
    
            // print_r($expnsData); die;
            if (!empty($expnsData)) {
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("expnsDataGetSuccess", $data['langType']);
                $this->apiResponse['totalPages'] = ceil($totalData / $limit);
                $this->apiResponse['totalExpenseForThisMonth'] = number_format($totalExpense,2);
                $this->apiResponse['data'] = $expnsData;
            } else {
                $this->apiResponse['status'] = "6";
                $this->apiResponse['message'] = $this->responseMessage("expnsDataGetFail", $data['langType']);
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

    public function pendingBills(Request $request) {
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
            
            $req = [
                'userId' => $user->id, 
                'status' => [0, 1], 
                'expnsStatus' => 1, 
                'fromCron' => [0,1]
            ];

            // echo date_default_timezone_get(); die;
            // print_r($req); die;
            $expnsData = [];
            $expnsData = $this->expenseModel->getData(array_merge($req, ['limit' => $limit, 'offset' => $offset]));
            $totalData = $this->expenseModel->getData($req, false, true);
            $totalExpense = array_sum(array_column($expnsData,'expnsAmount'));
    
            // print_r($expnsData); die;
            if (!empty($expnsData)) {
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("expnsDataGetSuccess", $data['langType']);
                $this->apiResponse['totalPages'] = ceil($totalData / $limit);
                $this->apiResponse['totalExpenseForThisMonth'] = number_format($totalExpense,2);
                $this->apiResponse['data'] = $expnsData;
            } else {
                $this->apiResponse['status'] = "6";
                $this->apiResponse['message'] = $this->responseMessage("expnsDataGetFail", $data['langType']);
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

    public function pastDueBills(Request $request) {
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
            
            $req = [
                'userId' => $user->id, 
                'status' => [0, 1], 
                'expnsStatus' => 2, 
                'fromCron' => [0,1]
            ];
            
            // echo date_default_timezone_get(); die;
            // print_r($req); die;
            $expnsData = [];
            $expnsData = $this->expenseModel->getData(array_merge($req, ['limit' => $limit, 'offset' => $offset]));
            $totalData = $this->expenseModel->getData($req, false, true);
            $totalExpense = array_sum(array_column($expnsData,'expnsAmount'));
    
            // print_r($expnsData); die;
            if (!empty($expnsData)) {
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("expnsDataGetSuccess", $data['langType']);
                $this->apiResponse['totalPages'] = ceil($totalData / $limit);
                $this->apiResponse['totalExpenseForThisMonth'] = number_format($totalExpense,2);
                $this->apiResponse['data'] = $expnsData;
            } else {
                $this->apiResponse['status'] = "6";
                $this->apiResponse['message'] = $this->responseMessage("expnsDataGetFail", $data['langType']);
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

    public function markAsCleared(Request $request) {
        $user = $request->authUser;
        $data = $request->data;
        // print_r($data); die;
        try {

            if (!isset($data['expenseId']) || empty($data['expenseId'])) {
                $this->apiResponse['message'] = $this->responseMessage("expenseIdRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }
            $data['expnsStatus'] = "0";

            $expnsData = [];
            $expnsData = $this->expenseModel->getData(['userId' => $user->id, 'id' => $data['expenseId'], 'status' => 1], TRUE);
            
            if (empty($expnsData)) {
                $this->apiResponse['status'] = "6";
                $this->apiResponse['message'] = $this->responseMessage("expnsDataNotFound", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            $expenseId = $this->expenseModel->setData(['expnsStatus' => $data['expnsStatus']],$data['expenseId']);
            // print_r($expenseId); die;

            if (!empty($expenseId)) {
                $expnsData = $this->expenseModel->getData(['userId' => $user->id, 'id' => $data['expenseId'], 'status' => [0, 1], /* 'formatedData' => $user->timeZone */], TRUE);
                
                $expnsData->createdDateInword = $this->commonModel->get_time_ago($expnsData->createdDate);
                $expnsData->updatedDateInword = (isset($expnsData->updatedDate))? $this->commonModel->get_time_ago($expnsData->updatedDate) :"";

                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("expenseCleared", $data['langType']);
                $this->apiResponse['data'] = $expnsData;
            } else {
                $this->apiResponse['status'] = "0";
                $this->apiResponse['message'] = $this->responseMessage("expenseUpdateFail", $data['langType']);
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

    public function markAsPending(Request $request) {
        $user = $request->authUser;
        $data = $request->data;
        // print_r($data); die;
        try {

            if (!isset($data['expenseId']) || empty($data['expenseId'])) {
                $this->apiResponse['message'] = $this->responseMessage("expenseIdRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }
            $data['expnsStatus'] = "1";

            $expnsData = [];
            $expnsData = $this->expenseModel->getData(['userId' => $user->id, 'id' => $data['expenseId'], 'status' => 1], TRUE);
            
            if (empty($expnsData)) {
                $this->apiResponse['status'] = "6";
                $this->apiResponse['message'] = $this->responseMessage("expnsDataNotFound", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }

            $expenseId = $this->expenseModel->setData(['expnsStatus' => $data['expnsStatus']],$data['expenseId']);
            // print_r($expenseId); die;

            if (!empty($expenseId)) {
                $expnsData = $this->expenseModel->getData(['userId' => $user->id, 'id' => $data['expenseId'], 'status' => [0, 1], /* 'formatedData' => $user->timeZone */], TRUE);
                
                $expnsData->createdDateInword = $this->commonModel->get_time_ago($expnsData->createdDate);
                $expnsData->updatedDateInword = (isset($expnsData->updatedDate))? $this->commonModel->get_time_ago($expnsData->updatedDate) :"";

                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("expenseUpdatedSuccess", $data['langType']);
                $this->apiResponse['data'] = $expnsData;
            } else {
                $this->apiResponse['status'] = "0";
                $this->apiResponse['message'] = $this->responseMessage("expenseUpdateFail", $data['langType']);
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

    public function taskSchedulingExpenseCronJob() {
        $expenseModel = new ExpenseModel;
        $expenseData = $expenseModel->getData(['expnsType' => [0,1], 'status' => 1, 'fromCron' => 1]);
        // echo "<pre>"; print_r($expenseData); die('kkk');

        if(!empty($expenseData)) {
            foreach($expenseData as $recurringExpense) {
                //0: Recurring Monthly 1: Recurring Weekly
                if($recurringExpense->expnsType == 0 && $recurringExpense->recurringDateDays == date('d')) { 
                    $this->inserDataInExpense($expenseModel, $recurringExpense);
                } else if($recurringExpense->expnsType == 1  && $recurringExpense->recurringDateDays == date('w')) { //w - A numeric representation of the day (0 for Sunday, 6 for Saturday)
                    $this->inserDataInExpense($expenseModel, $recurringExpense);
                }
            }
        }

    }
    
    function inserDataInExpense($expenseModel, $recurringExpense) {
        $request = [
            'userId' => $recurringExpense->userId,
            'mainCategoryId' => $recurringExpense->mainCategoryId,
            'transactionName' => $recurringExpense->transactionName,
            'expnsType' => $recurringExpense->expnsType,
            'recurringDateDays' => null,
            'fromCron' => 0, //0:From Cron Job 1:Manual
            'expnsDate' => null,
            'expnsStatus' => $recurringExpense->expnsStatus,
            'expnsAmount' => $recurringExpense->expnsAmount,
            'parentExpenseId' => $recurringExpense->id,
        ];
        $request['last_inserted_id'] = $expenseModel->setData($request);
        $request['inserted_time'] = date('d-m-Y h:i A');

        error_log("\n\n -------------------------------------" . date('c'). json_encode($request), 3, storage_path().'/worker/expense-cron-'.date('d-m-Y').'.log');
        
    }
}

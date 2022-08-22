<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Expense as ExpenseModel;
use App\Models\Income as IncomeModel;
use Throwable;
use Exception;

class DashboardController extends ApiController
{
    protected $expenseModel;
    protected $incomeModel;

    function __construct() {
        $this->expenseModel = new ExpenseModel;
        $this->incomeModel = new IncomeModel;
    }

    public function DashboardApi(Request $request){
        $user = $request->authUser;
        $data = $request->data;

        try{

            // $endDate = date('Y-m-d',strtotime("-1 day"));
            // $staticstart = date('Y-m-d',strtotime('+14 day'));
            // print_r($staticstart); die;

            //Monthly Income
            $reqMonthlyIncome = ['userId' => $user->id, 'status' => [0, 1], 'fromCron' => [0,1], 'incomeType' => [0,1,2],
                'dateRangeStart' => date('Y-m-01'),
                'dateRangeEnd' => date('Y-m-t')
            ];
            
            $monthlyIncomeData = [];
            $monthlyIncomeData = $this->incomeModel->getData(array_merge($reqMonthlyIncome));
            $totalData = $this->incomeModel->getData($reqMonthlyIncome, false, true);
            $totalIncome = array_sum(array_column($monthlyIncomeData,'incomeAmount'));

            if(!empty($monthlyIncomeData)) {
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("incomeDataGetSuccess", $data['langType']);
                $this->apiResponse['totalIncomeForThisMonth'] = number_format($totalIncome,2);
            }  else {
                // $this->apiResponse['status'] = "6";
                // $this->apiResponse['message'] = $this->responseMessage("incomeDataGetFail", $data['langType']);
            }

            //Spending Money
            $reqSpendingExpenses = [
                'userId' => $user->id, 
                'status' => [0,1], 
                'fromCron' => [0,1], 
                'expnsType' => [0,1,2],
                'expnsStatus' => [1,2],
                'dateRangeStart' => date('Y-m-01'),
                'dateRangeEnd' => date('Y-m-t'),
            ];

            $spendingExpnsData = [];
            $spendingExpnsData = $this->expenseModel->getData(array_merge($reqSpendingExpenses));
            $totalData = $this->expenseModel->getData($reqSpendingExpenses, false, true);
            $totalSpendingMoney = array_sum(array_column($spendingExpnsData,'expnsAmount'));

            // MonthlyExpense
            $reqMonthlyExpense = [
                'userId' => $user->id, 
                'status' => [0,1], 
                'fromCron' => [0,1], 
                'expnsType' => [0,1,2],
                'expnsStatus' => [0,1,2],
                'dateRangeStart' => date('Y-m-01'),
                'dateRangeEnd' => date('Y-m-t'),
            ];

            $monthlyExpnsData = [];
            $monthlyExpnsData = $this->expenseModel->getData(array_merge($reqMonthlyExpense));
            // print_r($monthlyExpnsData); die('kkkkk');
            $totalData = $this->expenseModel->getData($reqMonthlyExpense, false, true);
            $totalMonthlyExpense = array_sum(array_column($monthlyExpnsData,'expnsAmount'));

            $this->apiResponse['status'] = "1";
            $this->apiResponse['message'] = $this->responseMessage("expnsDataGetSuccess", $data['langType']);
            $this->apiResponse['currentPayPeriodData']['currentDate'] = date('M d');
            $this->apiResponse['currentPayPeriodData']['totalExpenseForThisMonth'] = number_format($totalMonthlyExpense,2);
            if (!empty($monthlyExpnsData )) {                
                $this->apiResponse['message'] = $this->responseMessage("expnsDataGetSuccess", $data['langType']);
                $this->apiResponse['currentPayPeriodData']['currentDate'] = date('M d');
                $this->apiResponse['currentPayPeriodData']['totalExpenseForThisMonth'] = number_format($totalMonthlyExpense,2);
                
                // $this->apiResponse['data'] = $monthlyExpnsData;
            }if(!empty($spendingExpnsData)){
                $this->apiResponse['currentPayPeriodData']['totalSpendingMoney'] = number_format($totalSpendingMoney,2);
            }else{
                $this->apiResponse['currentPayPeriodData']['totalSpendingMoney'] = "0.00";
            }

            //Next Pay Period
            $reqNextToPay = [
                'userId' => $user->id, 
                'status' => [0, 1], 
                'fromCron' => [0,1], 
                'expnsType' => [0,1,2],
                'expnsStatus' => [1,2],
                'dateRangeStart' => date('Y-m-d',strtotime('+7 days')),
                'dateRangeEnd' => date('Y-m-d',strtotime('+2 week')),
            ];
            // print_r($req); die;
            $nextToPayExpnsData = [];
            $nextToPayExpnsData = $this->expenseModel->getData(array_merge($reqNextToPay));
            $totalData = $this->expenseModel->getData($reqNextToPay, false, true);
            $totalNextToPayExpense = array_sum(array_column($nextToPayExpnsData,'expnsAmount'));
            $nextPayPeriod = date('M d',strtotime('+7 days'));
            $totalNextSpendingMoney = array_sum(array_column($nextToPayExpnsData,'expnsAmount'));

            //2 Next Pay Period
            $reqSecondNextToPay = [
                'userId' => $user->id, 
                'status' => [0, 1], 
                'fromCron' => [0,1], 
                'expnsType' => [0,1,2],
                'expnsStatus' => [1,2],
                'dateRangeStart' => date('Y-m-d',strtotime('+2 week')),
                'dateRangeEnd' => date('Y-m-d',strtotime('+3 week')),
            ];
            // print_r($req); die;
            $secondNextToPayExpnsData = [];
            $secondNextToPayExpnsData = $this->expenseModel->getData(array_merge($reqSecondNextToPay));
            $totalData = $this->expenseModel->getData($reqSecondNextToPay, false, true);
            $totalSecondNextToPayExpense = array_sum(array_column($secondNextToPayExpnsData,'expnsAmount'));
            $secondNextPayPeriod = date('M d',strtotime('+2 week'));
            $totalSecondSpendingMoney = array_sum(array_column($secondNextToPayExpnsData,'expnsAmount'));

            //3rd Next Pay Period
            $reqThirdNextToPay = [
                'userId' => $user->id, 
                'status' => [0, 1], 
                'fromCron' => [0,1], 
                'expnsType' => [0,1,2],
                'expnsStatus' => [1,2],
                'dateRangeStart' => date('Y-m-d',strtotime('+3 week')),
                'dateRangeEnd' => date('Y-m-d',strtotime('+4 week')),
            ];
            // print_r($req); die;
            $thirdNextToPayExpnsData = [];
            $thirdNextToPayExpnsData = $this->expenseModel->getData(array_merge($reqThirdNextToPay));
            $totalData = $this->expenseModel->getData($reqThirdNextToPay, false, true);
            $totalThirdNextToPayExpense = array_sum(array_column($thirdNextToPayExpnsData,'expnsAmount'));
            $thirdNextPayPeriod = date('M d',strtotime('+3 week'));
            $totalThirdSpendingMoney = array_sum(array_column($thirdNextToPayExpnsData,'expnsAmount'));

            $this->apiResponse['nextPayPeriodData']['nextPayPeriodDate'] = $nextPayPeriod;
            $this->apiResponse['nextPayPeriodData']['totalNextToPayExpense'] = '0.00';
            $this->apiResponse['nextPayPeriodData']['totalNextSpendingMoney'] = '0.00';
            if (!empty($nextToPayExpnsData)) {
                // $this->apiResponse['status'] = "1";
                // $this->apiResponse['message'] = $this->responseMessage("expnsDataGetSuccess", $data['langType']);
                $this->apiResponse['nextPayPeriodData']['nextPayPeriodDate'] = $nextPayPeriod;
                $this->apiResponse['nextPayPeriodData']['totalNextToPayExpense'] = number_format($totalNextToPayExpense,2);
                $this->apiResponse['nextPayPeriodData']['totalNextSpendingMoney'] = number_format($totalNextSpendingMoney,2);
            } 

            $this->apiResponse['nextPayPeriodData']['secondNextPayPeriod'] = $secondNextPayPeriod;
            $this->apiResponse['nextPayPeriodData']['totalSecondNextPayPeriod'] = '0.00';
            $this->apiResponse['nextPayPeriodData']['totalSecondSpendingMoney'] = '0.00';
            if (!empty($secondNextToPayExpnsData)) {
                $this->apiResponse['nextPayPeriodData']['secondNextPayPeriod'] = $secondNextPayPeriod;
                $this->apiResponse['nextPayPeriodData']['totalSecondNextPayPeriod'] = number_format($totalSecondNextToPayExpense,2);
                $this->apiResponse['nextPayPeriodData']['totalSecondSpendingMoney'] = number_format($totalSecondSpendingMoney,2);
            }
            
            $this->apiResponse['nextPayPeriodData']['thirdNextPayPeriod'] = $thirdNextPayPeriod;
            $this->apiResponse['nextPayPeriodData']['totalThirdNextToPayExpense'] = '0.00';
            $this->apiResponse['nextPayPeriodData']['totalThirdSpendingMoney'] = '0.00';
            if (!empty($thirdNextToPayExpnsData)) {
                $this->apiResponse['nextPayPeriodData']['thirdNextPayPeriod'] = $thirdNextPayPeriod;
                $this->apiResponse['nextPayPeriodData']['totalThirdNextToPayExpense'] = number_format($totalThirdNextToPayExpense,2);
                $this->apiResponse['nextPayPeriodData']['totalThirdSpendingMoney'] = number_format($totalThirdSpendingMoney,2);
            }else {
                // $this->apiResponse['status'] = "6";
                // $this->apiResponse['message'] = $this->responseMessage("expnsDataGetFail", $data['langType']);
            }

            //------------------------------------- brijesh  ---------------------------//
            $reqDining = ['userId' => $user->id, 'status' => [0, 1, 2], 'dashboard' => true];
            $diningExpnsData = $this->expenseModel->getData($reqDining);
            // print_r($reqDining); die;
            $this->apiResponse['totalExpense'] = array_sum(array_column($diningExpnsData, 'totalAmount'));
            $tmp = [];
            foreach($diningExpnsData as $k => $val) {
                $tmp[$k]['mainCategoryId'] = $val->mainCategoryId;
                $tmp[$k]['mainCategoryName'] = $val->mainCategoryName;
                $tmp[$k]['totalTransaction'] = $val->totalTransaction;
                $tmp[$k]['totalAmount'] = $val->totalAmount;
                $tmp[$k]['categoryThumbImage'] = $val->categoryThumbImage;
                $tmp[$k]['colorCode'] = $val->colorCode;
                $tmp[$k]['percentage'] = $val->totalAmount*100/$this->apiResponse['totalExpense'];
            }
            $this->apiResponse['mySpendingHabits'] = $tmp;
            //-------------------------------------  end ---------------------------//
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
}

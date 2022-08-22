<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MainCategory as MainCategorModel;
use Throwable;
use Exception;

class MainCategoryController extends ApiController
{
    protected $mainCategoryModel;

    function __construct() {
        $this->mainCategoryModel = new MainCategorModel;
    }

    public function setMainCategory(Request $request) {

        $data = $request->data;

        try {
            if (!isset($data['mainCategoryName']) || empty($data['mainCategoryName'])) {
                $this->apiResponse['message'] = $this->responseMessage("mainCategoryNameRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }
    
            $mainCategoryData = array();
            // die('kkkkkkkkkk');
            $mainCategoryData['mainCategoryName'] = $data['mainCategoryName'];
            $mainCategoryData['status'] = 1;
    
            $mainCategoryId = $this->mainCategoryModel->setData($mainCategoryData);
            
    
            if (!empty($mainCategoryId)) {
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("mainCategorySavedSuccess", $data['langType']);
    
                $mainCategoryData = $this->mainCategoryModel->getData(['id' => $mainCategoryId, 'status' => [0, 1]], TRUE);
                $this->apiResponse['data'] = $mainCategoryData;
            } else {
                $this->apiResponse['status'] = "0";
                $this->apiResponse['message'] = $this->responseMessage("mainCategorySaveFail", $data['langType']);
            }
    
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

    public function getMainCategory(Request $request) {
        $data = $request->data;
        
        try {
            $search = (isset($data['search']) && $data['search'] != '') ? $data['search'] : '';
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
    
            $mainCategoryData = [];
            $mainCategoryData = $this->mainCategoryModel->getData(['status' => [0, 1],'limit' => $limit, 'offset' => $offset, 'search' => $search]);
    
            // print_r($mainCategoryData); die;
            if (!empty($mainCategoryData)) {
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("mainCategoryDataGetSuccess", $data['langType']);
                $this->apiResponse['data'] = $mainCategoryData;
            } else {
                $this->apiResponse['status'] = "6";
                $this->apiResponse['message'] = $this->responseMessage("mainCategoryDataGetFail", $data['langType']);
            }
            return $this->sendResponse($this->apiResponse);
        } catch (Throwable $th) {
            $this->apiResponse['ThrowableError'] = "TH-ERROR-" .  $th->getCode();
            $this->apiResponse['message'] =  $th->getMessage();
            return $this->sendResponse($this->apiResponse);
            // $error_code = $th->getCode();
            // return response()->json(["status" =>  "TH-ERROR-" . $error_code, "message" => $th->getMessage()]);
        } catch (Exception $ex) {
            $error_code = $ex->getCode();
            return response()->json(["status" => "EX-ERROR-" . $error_code, "message" => $ex->getMessage()]);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use Throwable;
use Exception;
use App\Models\PlaidCategory as PlaidCategoryModel;

class CategoryController extends ApiController
{
    protected $plaidCategoryModel;

    function __construct() {
        $this->plaidCategoryModel = new PlaidCategoryModel;
    }

    public function plaidCategory(Request $request) {

        try{
            // $user = $request->authUser;
            // $data = $request->data;
            $rest =  $this->postPlaid('categories/get', [], 0);
            // echo "<pre>";
            // print_r($rest);die;
            $ids = [];
            foreach($rest->categories as $value) {
                $setPlaidCategoryData = [];
                $setPlaidCategoryData['plaidCategoryId'] = $value->category_id;
                // $setPlaidCategoryData['categoryId'] = '';
                $setPlaidCategoryData['plaidGroup'] = $value->group;
                $setPlaidCategoryData['plaidHierarchy'] = implode(',', $value->hierarchy);
                $ids[] = $this->plaidCategoryModel->setData($setPlaidCategoryData);        
            }
            $this->apiResponse['status'] = "1";
            $this->apiResponse['data'] = $ids;
            return $this->sendResponse($this->apiResponse);
        } catch (Throwable $th) {
            $this->apiResponse['ThrowableError'] = "TH-ERROR-" .  $th->getCode();
            $this->apiResponse['message'] =  $th->getMessage();
            return $this->sendResponse($this->apiResponse);
        } catch (Exception $ex) {
            $error_code = $ex->getCode();
            return response()->json(["status" => "EX-ERROR-" . $error_code, "message" => $ex->getMessage()]);
        }
        // $rest =  $this->post('get-user-profile', [], false);
        // echo "<pre>";
        // print_r($rest);
    }

    public function getPlaidCategory(Request $request) {
        $user = $request->authUser;
        $data = $request->data;

        try {
            $page_number = (isset($data['page']) && $data['page'] != '') ? $data['page'] : '1';
            $limit = (isset($data['limit']) && $data['limit'] != '') ? $data['limit'] : 50;
            if (isset($data['page']) && $data['page'] == 1) {
                $offset = 0;
            } else {
                if (isset($data['page']) && $data['page'] != '1') {
                    $offset = ($page_number * $limit) - $limit;
                } else {
                    $offset = 0;
                }
            }

            $req = ['status' => [0, 1],'orderby'=>'id'];
            $categoryData = [];
            $categoryData = $this->plaidCategoryModel->getData(array_merge($req, ['limit' => $limit, 'offset' => $offset]));
            $totalData = $this->plaidCategoryModel->getData($req, false, true);
            if (!empty($categoryData)) {
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("plaidCategoryFound", $data['langType']);
                $this->apiResponse['totalPages'] = ceil($totalData / $limit) . "";
                $this->apiResponse['data'] = $categoryData;
            } else {
                $this->apiResponse['status'] = "0";
                $this->apiResponse['message'] = $this->responseMessage("plaidCategoryFoundFail", $data['langType']);
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
}

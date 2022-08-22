<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController as ApiController;
use App\Models\User as User;
use App\Models\Common as CommonModel;
use App\Models\UserSocialAuth as UserSocialAuthModel;
use App\Models\Auth as AuthModel;
use App\Models\Background as BackgroundModel;
use App\Models\CMS as CMSModel;
use App\Models\AppUserFeedback as AppUserFeedbackModel;
use App\Models\Ticket as TicketModel;
use App\Models\Faq as FaqModel;
use App\Models\Notification as NotificationModel;
use DB;

class CommonController extends ApiController {
    protected $commonModel;
    protected $backgroundModel;
    protected $authModel;
    protected $userSocialAuthModel;
    protected $cmsModel;
    protected $appUserFeedbackModel;
    protected $ticketModel;
    protected $faqModel;
    protected $notificationModel;
    
    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    function __construct() {
        $this->commonModel = new CommonModel;
        $this->backgroundModel = new BackgroundModel;
        $this->authModel = new AuthModel;
        $this->userSocialAuthModel = new UserSocialAuthModel;
        $this->cmsModel = new CMSModel;
        $this->appUserFeedbackModel = new AppUserFeedbackModel;
        $this->ticketModel = new TicketModel;
        $this->faqModel = new FaqModel;
        $this->notificationModel = new NotificationModel;

        // $this->middleware('log.route', ['except' => [
        //     'media-upload', 'mediaUpload',
        // ]]);
    }

    
    public function mediaUpload(Request $request) {
        $uploadUrl = env('APP_URL').env('UPLOAD_URL');
        $pageURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        error_log("\n\n -------------------------------------" . date('c') . " \n" . $pageURL . " \n" . print_r($_POST, TRUE) . " \n" . print_r($_FILES, TRUE), 3, storage_path() . '/worker/api_fileuploadlog-' . date('d-m-Y') . '.log');
        
        ini_set('max_execution_time', 999999);
        ini_set('memory_limit', '999999M');
        ini_set('upload_max_filesize', '500M');
        ini_set('max_input_time', '-1');
        ini_set('max_execution_time', '-1');
        $imgData = [];
        $vid_types = ["mp4", "m4a", "MOV", "MPEG-4", "mpeg-4", "mov"];

        if (isset($_FILES['files']) && !empty($_FILES['files'])) {
            $image_type = $request->imageType;
            $upload_path = env('UPLOADPATH');
            $allowed_types = [".jpg", ".gif", ".png", ".jpeg", ".mp4", ".m4a", ".MOV", ".MPEG-4", ".mpeg-4", ".mov", ".pdf", ".doc", ".docx", ".webp"];
            
            foreach ($_FILES as $key => $file) {
                if (is_array($_FILES[$key]["name"])) {
                    foreach ($_FILES[$key]["name"] as $_key => $value) {
                        $_FILES['file']['name'] = $_FILES[$key]['name'][$_key];
                        $_FILES['file']['type'] = $_FILES[$key]['type'][$_key];
                        $_FILES['file']['tmp_name'] = $_FILES[$key]['tmp_name'][$_key];
                        $_FILES['file']['error'] = $_FILES[$key]['error'][$_key];
                        $_FILES['file']['size'] = $_FILES[$key]['size'][$_key];

                        $fileExt = $this->commonModel->getFileExtension($_FILES[$key]["name"][$_key]);
                        if (in_array($fileExt, $allowed_types)) {
                            $fileName = date('ymdhis') . $this->commonModel->random_string(6) . $fileExt;
                            $upload_dir = $upload_path . $fileName;
                            if (move_uploaded_file($_FILES[$key]["tmp_name"][$_key], $upload_dir)) {
                                $tmp = [];
                                $tmp['name'] = $fileName;
                                $tmp['url'] = $uploadUrl . $fileName;
                                $imgData[] = $fileName;
                            }
                        }
                    }
                } else {
                    
                    $fileExt = $this->commonModel->getFileExtension($_FILES[$key]["name"]);
                    if (in_array($fileExt, $allowed_types)) {
                        $fileName = date('ymdhis') . $this->commonModel->random_string(6) . $fileExt;
                        $upload_dir = $upload_path . $fileName;
                        if (move_uploaded_file($_FILES[$key]["tmp_name"], $upload_dir)) {
                            $tmp = [];
                            $tmp['name'] = $fileName;
                            $tmp['url'] = $uploadUrl . $fileName;
                            $imgData[] = $fileName;
                        }
                    }
                }
            }
        }
        if (!empty($imgData)) {
            $imgExtn = array('jpeg', 'gif', 'png', 'jpg', '.webp', 'JPG', 'PNG', 'GIF', 'JPEG', '.WEBP');
            $finalData = array();
            foreach ($imgData as $img) {
                $tmp = [];
                $tmp['mediaName'] = $img;
                $tmp['mediaBaseUrl'] = $uploadUrl . $img;
                $tmp['medialThumUrl'] = $uploadUrl . $img;
                //Generate Video thumb image
                $extention = pathinfo($img, PATHINFO_EXTENSION);
                if (in_array($extention, $vid_types)) {
                    $videoThumbImgName = date('ymdhis') . $this->commonModel->random_string(6) . '.jpg';
                    exec('ffmpeg  -i ' . $upload_path .$img . ' -deinterlace -an -ss 2 -f mjpeg -t 1 -r 1 -y ' . $upload_path .'/'. $videoThumbImgName . ' 2>&1');
                    $tmp['videoThumbImgName'] = $videoThumbImgName;
                    $tmp['videoThumbImgUrl'] = $uploadUrl . $videoThumbImgName;
                } else {
                    $tmp['videoThumbImgName'] = "";
                    $tmp['videoThumbImgUrl'] = "";
                    if (in_array($extention, ["pdf", "doc", "docx", "txt"])) {
                        $tmp['medialThumUrl'] = $uploadUrl . "default-document-preview.png";
                    } 
                }
                // ./Generate Video thumb image
                $finalData[] = $tmp;
            }
            $this->apiResponse['status'] = "1";
            $this->apiResponse['data'] = $finalData;
            $this->apiResponse['base_url'] = $uploadUrl;
            $this->apiResponse['message'] = $this->responseMessage("imageUploaded", "1");
        } else {
            $this->apiResponse['status'] = "0";
            $this->apiResponse['message'] = $this->responseMessage("noImageUpload", "1");
        }
        return $this->sendResponse($this->apiResponse);
    }

    public function getCMS(Request $request) {
        $data = $request->data;
        if (!isset($data['pageId']) || empty($data['pageId'])) {
            return $this->sendResponse($this->responseMessage('pageIdRequired',$data['langType']));
        }

        $cms = $this->cmsModel->getData(['status' => 1, 'key' => $data['pageId']], TRUE);
        if (!empty($cms)) {
            $this->apiResponse['status'] = "1";
            $this->apiResponse['message'] = $this->responseMessage("pageGetSuccess", $data['langType']);
            $this->apiResponse['data'] = $cms;
        } else {
            $this->apiResponse['status'] = "6";
            $this->apiResponse['message'] = $this->responseMessage("pageGetFail", $data['langType']);
        }
        return $this->sendResponse($this->apiResponse);
    }

    public function getAppFeedback(Request $request) {
        $user = $request->authUser;
        $data = $request->data;
        $getData = $this->appUserFeedbackModel->getData(['userId' => $user->id, 'status' => 1], true);

        if (!empty($getData)) {
            $this->apiResponse['status'] = "1";
            $this->apiResponse['message'] = $this->responseMessage("getMyAppFeedbackSuccess", $data['langType']);
            $this->apiResponse['data'] = $getData;
            return $this->sendResponse($this->apiResponse);
        } else {
            $this->apiResponse['status'] = "6";
            $this->apiResponse['message'] = $this->responseMessage("failToGetMyAppFeedback", $data['langType']);
            return $this->sendResponse($this->apiResponse);
        }
    }

    public function setAppFeedback(Request $request) {
        $user = $request->authUser;
        $data = $request->data;
        // print_r($data); die;
        if (!isset($data['rating']) || empty($data['rating'])) {
            return $this->sendResponse($this->responseMessage("ratingRequired", $data['langType']));
        }

        if (!isset($data['feedback']) || empty($data['feedback'])) {
            return $this->sendResponse($this->responseMessage("feedbackRequired", $data['langType']));
        }

        if (!in_array($data['rating'], [1,2,3,4,5]) ) {
            return $this->sendResponse($this->responseMessage("ratingRange", $data['langType']));
        }
        $input = [];
        $input['userId'] = $user->id;
        $input['rating'] = $data['rating'];
        $input['feedback'] = $data['feedback'];
        
        $appFeedbackId = "";
        $appFeedbackData = $this->appUserFeedbackModel->getData(['userId' => $user->id], true);
        if (!empty($appFeedbackData)) {
            $data['status'] = "1";
            $appFeedbackId = $this->appUserFeedbackModel->setData($input, $appFeedbackData->id);
        } else {
            $appFeedbackId = $this->appUserFeedbackModel->setData($input);
            // $user->adminemail = $_ENV['ADMIN_EMAIL'];
            // $user->givenRating = $data['rating'];
            // $user->feedback = $data['feedback'];
            // $this->Background_Model->userFeedbackMail($user);
        }

        if (!empty($appFeedbackId)) {
            $this->apiResponse['status'] = "1";
            $this->apiResponse['message'] = $this->responseMessage("saveAppFeedbackSuccess", $data['langType']);
        } else {
            $this->apiResponse['status'] = "0";
            $this->apiResponse['message'] = $this->responseMessage("failToSaveAppFeedback", $data['langType']);
        }
        return $this->sendResponse($this->apiResponse);
    }

    public function faq(Request $request) {
        // $user = $request->authUser;
        // print_r($user); die;
        $data = $request->data;
        //
        $page_number = (isset($data['page']) && $data['page'] != '') ? $data['page'] : '';
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

        $search = isset($data['search']) ? $data['search'] : "";
        $totalTicketCount = '0';
        if (isset($data['token']) || !empty($data['token'])) {
            $auth = $this->authModel->getData(['token' => $data['token']], true);
            // $auth = DB::table('tbl_auth')->where( array('token' => $data['token']))->first();
            // $auth = $this->db->get_where('tbl_auth', array('token' => $data['token']))->row();
            if ($auth) {
                $user = User::getData(['id' => $auth->userId, 'status' => 1], true);
                // $user = DB::table('tbl_users')->where(['id' => $auth->userId, 'status' => 1])->first();
                // $user = $this->db->get_where('tbl_users', ['id' => $auth->userId, 'status' => 1])->row();
                if ($user) {
                    $totalTicketCount = $this->ticketModel->getData(['userId' => $user->id, 'status' => [0, 1]], false, true);
                }
            }
        }
        $getData = $this->faqModel->getData([ 'search' => $search, 'status' => '1', 'limit' => $limit, 'offset' => $offset]);
        $totalData = $this->faqModel->getData(['search' => $search, 'status' => '1'], false, true);
        $getTiciketData = $this->ticketModel->getData(['status' => '1', 'userId' =>$user->id, 'limit'=> 2,'offset'=>0]);
        foreach($getTiciketData as $k => $ticket) {
            $getTiciketData[$k]->createdDateInword = $this->commonModel->get_time_ago($ticket->createdDate);
            $getTiciketData[$k]->updatedDateInword = (isset($ticket->updatedDate))? $this->commonModel->get_time_ago($ticket->updatedDate) :"";
        }
        if (!empty($getData)) {
            $this->apiResponse['status'] = "1";
            $this->apiResponse['message'] = $this->responseMessage("faqlistSuccess", $data['langType']);
            $this->apiResponse['totalPages'] = ceil($totalData / $limit) . "";
            $this->apiResponse['totalTicketCount'] = $totalTicketCount;
            $this->apiResponse['data'] = $getData;
            $this->apiResponse['ticketData'] = $getTiciketData;
            return $this->sendResponse($this->apiResponse);
        } else {
            $this->apiResponse['status'] = "6";
            $this->apiResponse['message'] = $this->responseMessage("faqlistFail", $data['langType']);
            $this->apiResponse['totalPages'] = ceil($totalData / $limit) . "";
            $this->apiResponse['totalTicketCount'] = $totalTicketCount;
            $this->apiResponse['data'] = [];
            return $this->sendResponse($this->apiResponse);
        }
    }

    public function faqDetails(Request $request) {
        $data = $request->data;
        $user = $request->authUser;
        // print_r($request->authUser); die;
        if (!isset($data['faqId']) || empty($data['faqId'])) {
            $this->apiResponse['message'] = $this->responseMessage("faqIdRequired", $data['langType']);
            return $this->sendResponse($this->apiResponse);
        }

        $getData = $this->faqModel->getData(['status' => '1', 'id' => $data['faqId']], TRUE);
        // $getTiciketData = $this->ticketModel->getData(['status' => '1', 'userId' =>$user->id, 'limit'=> 2,'offset'=>0]);
        // print_r($user->id); die;
        if (!empty($getData)) {
            $this->apiResponse['status'] = "1";
            $this->apiResponse['message'] = $this->responseMessage("getFaqDetailSuccess", $data['langType']);
            $this->apiResponse['data'] = $getData;
            // $this->apiResponse['ticketData'] = $getTiciketData;
            return $this->sendResponse($this->apiResponse);
        } else {
            $this->apiResponse['status'] = "0";
            $this->apiResponse['message'] = $this->responseMessage("getFaqDetailFail", $data['langType']);
            $this->apiResponse['data'] = [];
            return $this->sendResponse($this->apiResponse);
        }
    }

    public function getNotificationsList(Request $request) {
        $user = $request->authUser;
        $data = $request->data;
        // print_r($user); die;
        $page_number = (isset($data['page']) && $data['page'] != '') ? $data['page'] : '';
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

        $notiData = $this->notificationModel->getData(['send_to'=>$user->id,'status'=>[0,1],'limit'=> $limit,'offset'=>$offset]);
        $totalData = $this->notificationModel->getData(['send_to'=>$user->id,'status'=>[0,1]],false,true);
        // print_r($totalData); die;

        $responseData = array();
        if(!empty($notiData)){
            foreach($notiData as $value){
                if($value->status == 1){
                    $this->notificationModel->setData(['status'=>0],$value->id);
                }
                $value->time = $this->commonModel->get_time_ago($value->createdDate);
                $responseData[] = $value;
            }
        }
        if(!empty($responseData)){
            $this->apiResponse['status'] = "1";
            $this->apiResponse['message'] = $this->responseMessage("getNotificationSuccess", $data['langType']);
            $this->apiResponse['totalPages'] = ceil($totalData/$limit)."";
            $this->apiResponse['data'] = $responseData;
        }else{
            $this->apiResponse['status'] = "6";
            $this->apiResponse['message'] = $this->responseMessage(($offset > 0 ? 'allcatchedUp' : "notFoundNotification"), $data['langType']);
            $this->apiResponse['totalPages'] = ceil($totalData/$limit)."";
        }
        return $this->sendResponse($this->apiResponse);
    }

    public function getUnreadNotificationsCount(Request $request){
        $user = $request->authUser;
        $data = $request->data;
        $this->apiResponse['status'] = "1";
        $this->apiResponse['data'] = $this->notificationModel->getData(['send_to'=>$user->id,'status'=>1],false,true);
        $this->apiResponse['message'] = $this->responseMessage("getUnreadNotificationCount", $data['langType']);
        return $this->sendResponse($this->apiResponse);
    }


}
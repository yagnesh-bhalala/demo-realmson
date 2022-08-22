<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Common as CommonModel;
use App\Models\Ticket as TicketModel;
use App\Models\User;
use Throwable;
use Exception;

class SupportController extends ApiController
{
    protected $commonModel;
    protected $ticketModel;

    function __construct() {
        $this->commonModel = new CommonModel;
        $this->ticketModel = new TicketModel;
    }

    public function setTicket(Request $request) {
        $user = $request->authUser;
        $data = $request->data;

        if (!isset($data['title']) || empty($data['title'])) {
            $this->apiResponse['message'] = $this->responseMessage("titleRequired", $data['langType']);
            return $this->sendResponse($this->apiResponse);
        }

        if (!isset($data['description']) || empty($data['description'])) {
            $this->apiResponse['message'] = $this->responseMessage("descRequired", $data['langType']);
            return $this->sendResponse($this->apiResponse);
        }

        $titcketData = array();
        $titcketData['userId'] = $user->id;
        $titcketData['title'] = $data['title'];
        $titcketData['description'] = $data['description'];
        $titcketData['priority'] = "0";
        $titcketData['closedDate'] = 0;
        $titcketData['reopenDate'] = 0;
        // print_r($titcketData); die;

        $titcketId = $this->ticketModel->setData($titcketData);
        

        if (!empty($titcketId)) {
            $this->apiResponse['status'] = "1";
            $this->apiResponse['message'] = $this->responseMessage("ticketSavedSuccess", $data['langType']);

            $titcketData = $this->ticketModel->getData(['userId' => $user->id, 'id' => $titcketId, 'status' => [0, 1]], TRUE);
            $titcketData->createdDateInword = $this->commonModel->get_time_ago($titcketData->createdDate);
            // print_r($titcketData->createdDateInword); die;
            $titcketData->updatedDateInword = (isset($titcketData->updatedDate))? $this->commonModel->get_time_ago($titcketData->updatedDate) :"";
            $this->apiResponse['data'] = $titcketData;
        } else {
            $this->apiResponse['status'] = "0";
            $this->apiResponse['message'] = $this->responseMessage("ticketSaveFail", $data['langType']);
        }

        return $this->sendResponse($this->apiResponse);
    }

    public function getTicket(Request $request) {
        $user = $request->authUser;
        $data = $request->data;

        $type = ['0', '1'];
        if (isset($data['type']) && !empty($data['type']) ) {
            if (in_array($data['type'], ['1','2','3'])) {
                if ($data['type'] == '2') {
                    $type = '1';
                } else if ($data['type'] == '3') {
                    $type = '0';
                }
            }
        }
        // print_r($type); die;

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

        $titcketData = array();
        $titcketData = $this->ticketModel->getData(['userId' => $user->id,'status' => $type,'search' => $search, 'type' => $type,/* "formatedData" => $user->timeZone, */ 'limit' => $limit,'offset' => $offset]);
        foreach($titcketData as $k => $ticket) {
            $titcketData[$k]->createdDateInword = $this->commonModel->get_time_ago($ticket->createdDate);
            $titcketData[$k]->updatedDateInword = (isset($ticket->updatedDate))? $this->commonModel->get_time_ago($ticket->updatedDate) :"";
        }
        $totalData = $this->ticketModel->getData(['userId' => $user->id, 'status' => $type, 'search' => $search], false, true);

        if (!empty($titcketData)) {
            $this->apiResponse['status'] = "1";
            $this->apiResponse['message'] = $this->responseMessage("ticketDataGetSuccess", $data['langType']);
            $this->apiResponse['totalPages'] = ceil($totalData / $limit) . "";
            $this->apiResponse['data'] = $titcketData;
        } else {
            $this->apiResponse['status'] = "6";
            $this->apiResponse['message'] = $this->responseMessage("ticketDataGetFail", $data['langType']);
            $this->apiResponse['totalPages'] = ceil($totalData / $limit) . "";
        }
        return $this->sendResponse($this->apiResponse);
    }

    public function getTicketDetail(Request $request) {
        $user = $request->authUser;
        $data = $request->data;
        // print_r($data); die;

        if (!isset($data['ticketId']) || empty($data['ticketId'])) {
            $this->apiResponse['message'] = $this->responseMessage("ticketIdRequired", $data['langType']);
            return $this->sendResponse($this->apiResponse);
        }

        $titcketData = $this->ticketModel->getData(['userId' => $user->id, 'id' => $data['ticketId'], 'status' => [0, 1]], TRUE);
        // print_r($titcketData); die;
        if (empty($titcketData)) {
            $this->apiResponse['status'] = "6";
            $this->apiResponse['message'] = $this->responseMessage("ticketNotFound", $data['langType']);
            return $this->sendResponse($this->apiResponse);
        }
        
        $titcketData->createdDateInword = $this->commonModel->get_time_ago($titcketData->createdDate);
        $titcketData->updatedDateInword = (isset($titcketData->updatedDate))? $this->commonModel->get_time_ago($titcketData->updatedDate) :"";
        $this->apiResponse['status'] = "1";
        $this->apiResponse['message'] = $this->responseMessage("ticketDataGetSuccess", $data['langType']);
        $this->apiResponse['data'] = $titcketData;

        return $this->sendResponse($this->apiResponse);
    }

    public function reopenTicket(Request $request) {
        $user = $request->authUser;
        $data = $request->data;

        if (!isset($data['ticketId']) || empty($data['ticketId'])) {
            $this->apiResponse['message'] = $this->responseMessage("ticketIdRequired", $data['langType']);
            return $this->sendResponse($this->apiResponse);
        }

        $titcketData = [];
        $titcketData = $this->ticketModel->getData(['userId' => $user->id, 'id' => $data['ticketId'], 'status' => 0], TRUE);
        // print_r($titcketData); die;
        if (empty($titcketData)) {
            $this->apiResponse['status'] = "6";
            $this->apiResponse['message'] = $this->responseMessage("ticketNotFound", $data['langType']);
            return $this->sendResponse($this->apiResponse);
        }

        $titcketId = $this->ticketModel->setData(['status' => 1, 'reopenDate' => time()], $data['ticketId']);
        // print_r($titcketId); die;
        if (!empty($titcketId)) {
            $titcketData = $this->ticketModel->getData(['userId' => $user->id, 'id' => $data['ticketId'], 'status' => [0, 1], /* 'formatedData' => $user->timeZone */], TRUE);
            
            $titcketData->createdDateInword = $this->commonModel->get_time_ago($titcketData->createdDate);
            $titcketData->updatedDateInword = (isset($titcketData->updatedDate))? $this->commonModel->get_time_ago($titcketData->updatedDate) :"";

            $this->apiResponse['status'] = "1";
            $this->apiResponse['message'] = $this->responseMessage("ticketReopenSuccess", $data['langType']);
            $this->apiResponse['data'] = $titcketData;
        } else {
            $this->apiResponse['status'] = "0";
            $this->apiResponse['message'] = $this->responseMessage("ticketReopenFail", $data['langType']);
        }

        return $this->sendResponse($this->apiResponse);
    }

    public function setTicketReply(Request $request) {
		$user = $request->authUser;
        $data = $request->data;
		
        if (!isset($data['ticketId']) || empty($data['ticketId'])) {
            $this->apiResponse['message'] = $this->responseMessage("ticketIdRequired", $data['langType']);
            return $this->sendResponse($this->apiResponse);
        }
        
        if (!isset($data['description']) || empty($data['description'])) {
            $this->apiResponse['message'] = $this->responseMessage("descRequired", $data['langType']);
            return $this->sendResponse($this->apiResponse);
        }
        
        if (!isset($data['replyType']) || empty($data['replyType'])) {
            $this->apiResponse['message'] = $this->responseMessage("replyTypeRequired", $data['langType']);
            return $this->sendResponse($this->apiResponse);
        }
        
        $titcketData = $this->ticketModel->getData([
            'userId' => $user->id, 
            'id' => $data['ticketId'], 
            'status' => [0,1]
        ], TRUE);
        if(empty($titcketData)) {
			$this->apiResponse['status'] = "0";
            $this->apiResponse['message'] = $this->responseMessage("ticketNotFound", $data['langType']);
			return $this->sendResponse($this->apiResponse);
		}
		if($titcketData->status == 0) {
			$this->apiResponse['status'] = "0";
            $this->apiResponse['message'] = $this->responseMessage("ticketClosed", $data['langType']);
			return $this->sendResponse($this->apiResponse);
		}
        
        $titcketReplyData                   = [];
        $titcketReplyData['ticketId']       = $data['ticketId'];
        $titcketReplyData['description']    = $data['description'];
        $titcketReplyData['replyType']      = $data['replyType'];
        $titcketReplyData['forReply']       = '2';
        $titcketId = $this->ticketModel->setTicketReplyData($titcketReplyData);
        
        if($titcketId) {
            $this->apiResponse['status'] = "1";
	        $this->apiResponse['message'] = $this->responseMessage("ticketReplySavedSuccess", $data['langType']);
            return $this->sendResponse($this->apiResponse);
	    } else {
            $this->apiResponse['status'] = "0";
            $this->apiResponse['message'] = $this->responseMessage("ticketReplySaveFail", $data['langType']);
            return $this->sendResponse($this->apiResponse);
        }
        
        $this->sendResponse($this->apiResponse);
    }

    public function getTicketReply(Request $request) {
        
        $user = $request->authUser;
        $data = $request->data;
        // print_r($data); die;

        try {
            if (!isset($data['ticketId']) || empty($data['ticketId'])) {
                $this->apiResponse['message'] = $this->responseMessage("ticketIdRequired", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }
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
            $chats = [];
            $chats = $this->ticketModel->getTicketReply([
                'ticketId'=>$data['ticketId'],
                'userId' => $user->id,
                'search' => $search,
                'status' => [0, 1],
                'limit' => $limit, 
                'offset' => $offset,
            ]);
            foreach($chats as $k => $ticket) {
                $chats[$k]->createdDateInword = $this->commonModel->get_time_ago($ticket->createdDate);
                $chats[$k]->updatedDateInword = (isset($ticket->updatedDate))? $this->commonModel->get_time_ago($ticket->updatedDate) :"";
            }
    
            $totalData = $this->ticketModel->getTicketReply(['userId' => $user->id, 'status' => [0, 1], 'search' => $search], false, true);
            // print_r($chats); die;
            if (!empty($chats)) {
                // $this->db->select('CONCAT('.$this->tbl_users . '.firstName," ",'.$this->tbl_users . '.lastName) as senderName');
                // $this->db->select($this->tbl_users . '.firstName');
                // $this->db->select($this->tbl_users . '.lastName');

                foreach($chats as $k => $val) {
                    // print_r($val);die;
                    $usrModel =  User::getData(['id' => $val->tkt_userId], true);
                    // print_r($usrModel);die;

                    $chats[$k]->senderName = $usrModel->firstName . ' ' . $usrModel->lastName;
                    $chats[$k]->firstName = $usrModel->firstName;
                    $chats[$k]->lastName = $usrModel->lastName;
                }
                $this->apiResponse['status'] = "1";
                $this->apiResponse['message'] = $this->responseMessage("ticketDataGetSuccess", $data['langType']);
                $this->apiResponse['totalPages'] = ceil($totalData / $limit) . "";
                $this->apiResponse['data'] = $chats;
                return $this->sendResponse($this->apiResponse);
            } else {
                $this->apiResponse['status'] = "6";
                $this->apiResponse['message'] = $this->responseMessage("ticketDataGetFail", $data['langType']);
                return $this->sendResponse($this->apiResponse);
            }
    
            $this->sendResponse($this->apiResponse);
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
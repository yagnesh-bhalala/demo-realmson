<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Cookie;
use GuzzleHttp\Client;

class ApiController extends Controller {
    const ERROR = "0";
    const SUCCESS = "1";
    const FAIL_AUTH = "2";
    const VERIFY = "3";
    const EMAIL_REQUIRED = "4";
    const BLOCKED = "5";
    const NO_DATA = "6";
    const PHONE_REQUIRED = "7";
    const HTTP_OK = 200;

    public function sendResponse($response) {
        if (is_array($response) && !isset($response['status'])) {
            $response['status'] = ApiController::ERROR;
        } else if (gettype($response) == "string") {
            $res['status'] = ApiController::ERROR;
            $res['message'] = $response;
            $response = $res;
        }

    	return response()->json($this->cleanArrayOutPut($response), 200);
    }

    public function sendError($error, $errorMessages = [], $code = 200) {
        die('--------- please contact admin -------');
        $response = [
            'status' => ApiController::ERROR,
            'message' => $error,
        ];

        return response()->json($response, $code);
    }

    public function responseMessage($key = "",$langType = "1"){
        if(!empty($key)) {
            $messageData = DB::table('tbl_apiresponse')->where('key',$key)->first();
            // print_r($langType); die;
            if($langType == "1") { // English
                return isset($messageData->value_en) ? $messageData->value_en : $key;
                //return isset($messageData->value_en) ? $messageData->value_en : "Message not found";
            } else if($langType == "2") { // Spanish
                return isset($messageData->value_es) ? $messageData->value_es : $key;
            } else if($langType == "3") { // French
                return isset($messageData->value_fr) ? $messageData->value_fr : $key;
            } else if($langType == "4") { // German
                return isset($messageData->value_de) ? $messageData->value_de : $key;
            }
        }
        
        return "Message not found";
    }

    public function cleanArrayOutPut($data) {
        if (!is_array($data) && !is_object($data)) {
            return (string) $data;
        }
        foreach ($data as $key => $value) {
            if (is_object($value)) {
                $value = (array) $value;
            }
            if (is_array($value)) {
                $data[$key] = $this->cleanArrayOutPut($value);
            } else {
                $data[$key] = (string) $value;
            }
        }
        return $data;
    }

    public function getToken($length) {
        die('APICONTROLLER');
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }
        return $key;
    }

    public function postPlaid($url, $post=[], $json=true) {
        $base_uri = 'https://sandbox.plaid.com/';/*categories/get*/

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $base_uri.$url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        if($json){
            return $response;
        } else {
            return json_decode($response);
        }
        curl_close($curl);
        // echo "<pre>";
        // print_r(json_decode($response));
    }

    // public function post($url, $post = [], $json=true) {
    //     $base_uri = env('APP_URL').'api/';
    //     $token = 't1';
    //     if (Cookie::get('frontToken') !== null) {
    //         $token = Cookie::get('frontToken');
    //     }
    //     // if(!empty($this->_ci->input->cookie('frontToken',true))){
    //     //     $this->token = $this->_ci->input->cookie('frontToken',true);
    //     // }
    //     $post['langType'] = "1";
    //     $post['token'] = $token;
    //     $request['data'] = $post;
    //     try {
    //         $clients = new Client(['base_uri' => $base_uri, 'timeout'  => 2.0]);
    //         $params['headers'] = ['Content-Type' => 'application/json'];
    //         $params['json'] = $request;
    //         $response = $clients->post($url, $params);
    //         $business = $response->getBody();
    //         if($json == true){
    //             return $business;
    //         } else {
    //             return json_decode($business);
    //         }

    //         // $curl = curl_init();
    //         // curl_setopt_array($curl, array(
    //         //     CURLOPT_URL => $apiUrl,
    //         //     CURLOPT_RETURNTRANSFER => true,
    //         //     CURLOPT_ENCODING => "",
    //         //     CURLOPT_MAXREDIRS => 10,
    //         //     CURLOPT_TIMEOUT => 0,
    //         //     CURLOPT_FOLLOWLOCATION => true,
    //         //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         //     CURLOPT_CUSTOMREQUEST => "POST",
    //         //     CURLOPT_POSTFIELDS =>json_encode($request),
    //         //     CURLOPT_HTTPHEADER => array(
    //         //         "Content-Type: application/json"
    //         //     ),
    //         // ));
    //         // $response = curl_exec($curl);
    //         // curl_close($curl);
    //         // if($json == 'true'){
    //         //     return $response;
    //         // }else{
    //         //     return json_decode($response);
    //         // }
    //     }catch (Exception $e) {
    //         return json_encode(["status" => '0','message'=>'Something went wrong from server']);
    //     }
    // }

}
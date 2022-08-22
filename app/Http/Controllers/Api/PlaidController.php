<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController as ApiController;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PlaidController extends ApiController
{
    protected $client_id ,$secret;
    public function __construct() {
        $this->client_id = env('CLIENT_ID');
        $this->secret = env('SECRET_KEY');
    }

    public function linkToken($url, $post=[], $json=true) {
        $base_uri = 'https://sandbox.plaid.com/';

        $client_id = $this->client_id;
        $secret = $this->secret;
        $client_name = $post['client_name'];
        $country_codes = $post['country_codes'];
        $language = $post['language'];
        $client_user_id = $post['client_user_id'];
        $products = $post['products'];
        $str = implode(',',$country_codes);
        $strProduct = implode(',',$products);
        
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
            CURLOPT_POSTFIELDS =>'{
                "client_id": "'.$client_id.'",
                "secret": "'.$secret.'",
                "client_name": "'.$client_name.'",
                "country_codes": ["'.$str.'"],
                "language": "en",
                "user": {
                    "client_user_id": "'.$client_user_id.'"
                },
                "products": [
                    "'.$strProduct.'"
                ]
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        
        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function linkTokenUpdate($url, $post=[], $json=true) {
        $base_uri = 'https://sandbox.plaid.com/';

        $client_id = $this->client_id;
        $secret = $this->secret;
        $client_name = $post['client_name'];
        $country_codes = $post['country_codes'];
        $language = $post['language'];
        $client_user_id = $post['client_user_id'];
        $access_token = $post['access_token'];
        $str = implode(',',$country_codes);

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
            CURLOPT_POSTFIELDS =>'{
                "client_id": "'.$client_id.'",
                "secret": "'.$secret.'",
                "client_name": "'.$client_name.'",
                "country_codes": ["'.$str.'"],
                "language": "en",
                "user": {
                    "client_user_id": "'.$client_user_id.'"
                },
                "access_token": "'.$access_token.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        // echo $response;
        return json_decode($response);
    }

    public function transaction($url, $post=[], $json=true) {
        $base_uri = 'https://sandbox.plaid.com/';

        $client_id = $this->client_id;
        $secret = $this->secret;
        $access_token = $post['access_token'];
        $start_date = $post['start_date'];
        $end_date = $post['end_date'];
        // echo '{
        //     "client_id": "'.$client_id.'",
        //     "secret": "'.$secret.'",
        //     "access_token": "'.$access_token.'",
        //     "start_date": "'.$start_date.'",
        //     "end_date": "'.$end_date.'",
        // }'; die('llll');
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
            CURLOPT_POSTFIELDS =>'{
                "client_id": "'.$client_id.'",
                "secret": "'.$secret.'",
                "access_token": "'.$access_token.'",
                "start_date": "'.$start_date.'",
                "end_date": "'.$end_date.'",
                "options": {
                    "count": 500,
                    "offset": 0
                }
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        echo $response;
    }

    public function publicToken($url, $post=[], $json=true) {
        $base_uri = 'https://sandbox.plaid.com/';

        $post['client_id'] = $this->client_id;
        $post['secret'] = $this->secret;

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $base_uri.$url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
        if ($json) {
            return $response;
        } else {
            return json_decode($response);
        }

    }

    public function commonCurlRequest($url, $post=[], $json=true) {
        $base_uri = 'https://sandbox.plaid.com/';
        $post['client_id'] = $this->client_id;
        $post['secret'] = $this->secret;
        
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $base_uri.$url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($post),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
        ]);
        
        $response = curl_exec($curl);

        curl_close($curl);

        if ($json) {
            return $response;
        } else {
            return json_decode($response);
        }
    }
}

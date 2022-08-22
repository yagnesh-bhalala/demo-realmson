<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Common as commonModel;
use Exception;
use ReceiptValidator\iTunes\Validator as iTunesValidator;

class Background extends Model {

    public function userSignupMail($data) {
        $user = User::getData(['id'=>$data],TRUE);
        // echo view('mail.signup', ['user'=>$user]);
        try {
            Mail::send('mail.signup', ['user'=>$user], function($message) use($user) {
                // print_r($user->email); die;
                // die('kkkkkk');
                $message->from('test@greatwisher.com');
                $message->to($user->email);
                $message->subject('Verification code of '.env('APP_NAME'));
            });
        } catch(Exception $e) {
            
            return  $e->getMessage();
            // return FALSE;
        }

        return true;
    }

    public function userVerificationMail($data) {
        if (empty($data)) {
            return false;
        }
        $user = User::getData(['id' => $data], TRUE);
        if (empty($user)) {
            return false;
        }

        try {
            if (!empty($user->email)) {
                Mail::send('mail.userVerification', ['user' => $user], function($message) use($user) {
                    $message->from('test@greatwisher.com');
                    $message->to($user->email);
                    $message->subject(env('EMAIL_SUBJECT') . " account verification code.");
                });
            }
        } catch(Exception $e) {
            return FALSE;
        }

        return true;
    }

    public function userParentsVerificationMail($data, $parentVerificationCode) {
        if (empty($data)) {
            return false;
        }

        $user = User::getData('id',$data);
        if (empty($user)) {
            return false;
        }

        if (!empty($user->parentEmail)) {
            $verifyId = urlencode(base64_encode($parentVerificationCode.'/'.$user->parentEmail));
            Mail::send('mail.userParentVerfication', ['user' => $user, 'verifyId' => $verifyId], function($message) use($user) {
                $message->from('test@greatwisher.com');
                $message->to($user->parentEmail);
                $message->subject(env('EMAIL_SUBJECT') . "Parent account verification code.");
            });
        }
    }

    public function userForgotPasswordMail($data){
        $user = User::getData(['id' => $data],TRUE);
        if (empty($user)) {
            return false;
        }
        try {
            Mail::send('mail.forgotPassword', ['user'=>$user,], function($message) use($user) {
                $message->from('test@greatwisher.com');
                $message->to($user->email);
                $message->subject('Forgot password code of '.env('APP_NAME'));
            });
        } catch (Exception $e) {
            return FALSE;
        }

        return true;
    }

    public function adminForgotPasswordMail($data,$password){
        $userData = $data;
        try {
            $getData = (array)$userData;
            Mail::send('mail.adminForgot', ['userData'=>$getData,'password'=>$password], function($message) use($getData) {
                $message->from('test@greatwisher.com');
                $message->to($getData['email']);
                $message->subject('Admin new password of '.env('APP_NAME'));
            });
        } catch(Exception $e) {
            return FALSE;
        }
    }

    public static function adminContactUs($data) {
        try {
            Mail::send('mail.adminContactUs', ['data'=>$getData], function($message) use($getData) {
                $message->from('test@greatwisher.com');
                $message->to(env('ADMIN_EMAIL'));
                $message->subject('Contact us for '.env('APP_NAME'));
            });
        } catch(Exception $e) {
            return FALSE;
        }
    }

    public function inAppValidator($receiptBase64Data) {
        $validator = new iTunesValidator(iTunesValidator::ENDPOINT_SANDBOX); // Or iTunesValidator::ENDPOINT_SANDBOX if sandbox testing ENDPOINT_PRODUCTION
        // $receiptBase64Data = 'ewoJInNpZ25hdHVyZSIgPSAiQXBNVUJDODZBbHpOaWtWNVl0clpBTWlKUWJLOEVkZVhrNjNrV0JBWHpsQzhkWEd1anE0N1puSVlLb0ZFMW9OL0ZTOGNYbEZmcDlZWHQ5aU1CZEwyNTBsUlJtaU5HYnloaXRyeVlWQVFvcmkzMlc5YVIwVDhML2FZVkJkZlcrT3kvUXlQWkVtb05LeGhudDJXTlNVRG9VaFo4Wis0cFA3MHBlNWtVUWxiZElWaEFBQURWekNDQTFNd2dnSTdvQU1DQVFJQ0NHVVVrVTNaV0FTMU1BMEdDU3FHU0liM0RRRUJCUVVBTUg4eEN6QUpCZ05WQkFZVEFsVlRNUk13RVFZRFZRUUtEQXBCY0hCc1pTQkpibU11TVNZd0pBWURWUVFMREIxQmNIQnNaU0JEWlhKMGFXWnBZMkYwYVc5dUlFRjFkR2h2Y21sMGVURXpNREVHQTFVRUF3d3FRWEJ3YkdVZ2FWUjFibVZ6SUZOMGIzSmxJRU5sY25ScFptbGpZWFJwYjI0Z1FYVjBhRzl5YVhSNU1CNFhEVEE1TURZeE5USXlNRFUxTmxvWERURTBNRFl4TkRJeU1EVTFObG93WkRFak1DRUdBMVVFQXd3YVVIVnlZMmhoYzJWU1pXTmxhWEIwUTJWeWRHbG1hV05oZEdVeEd6QVpCZ05WQkFzTUVrRndjR3hsSUdsVWRXNWxjeUJUZEc5eVpURVRNQkVHQTFVRUNnd0tRWEJ3YkdVZ1NXNWpMakVMTUFrR0ExVUVCaE1DVlZNd2daOHdEUVlKS29aSWh2Y05BUUVCQlFBRGdZMEFNSUdKQW9HQkFNclJqRjJjdDRJclNkaVRDaGFJMGc4cHd2L2NtSHM4cC9Sd1YvcnQvOTFYS1ZoTmw0WElCaW1LalFRTmZnSHNEczZ5anUrK0RyS0pFN3VLc3BoTWRkS1lmRkU1ckdYc0FkQkVqQndSSXhleFRldngzSExFRkdBdDFtb0t4NTA5ZGh4dGlJZERnSnYyWWFWczQ5QjB1SnZOZHk2U01xTk5MSHNETHpEUzlvWkhBZ01CQUFHamNqQndNQXdHQTFVZEV3RUIvd1FDTUFBd0h3WURWUjBqQkJnd0ZvQVVOaDNvNHAyQzBnRVl0VEpyRHRkREM1RllRem93RGdZRFZSMFBBUUgvQkFRREFnZUFNQjBHQTFVZERnUVdCQlNwZzRQeUdVakZQaEpYQ0JUTXphTittVjhrOVRBUUJnb3Foa2lHOTJOa0JnVUJCQUlGQURBTkJna3Foa2lHOXcwQkFRVUZBQU9DQVFFQUVhU2JQanRtTjRDL0lCM1FFcEszMlJ4YWNDRFhkVlhBZVZSZVM1RmFaeGMrdDg4cFFQOTNCaUF4dmRXLzNlVFNNR1k1RmJlQVlMM2V0cVA1Z204d3JGb2pYMGlreVZSU3RRKy9BUTBLRWp0cUIwN2tMczlRVWU4Y3pSOFVHZmRNMUV1bVYvVWd2RGQ0TndOWXhMUU1nNFdUUWZna1FRVnk4R1had1ZIZ2JFL1VDNlk3MDUzcEdYQms1MU5QTTN3b3hoZDNnU1JMdlhqK2xvSHNTdGNURXFlOXBCRHBtRzUrc2s0dHcrR0szR01lRU41LytlMVFUOW5wL0tsMW5qK2FCdzdDMHhzeTBiRm5hQWQxY1NTNnhkb3J5L0NVdk02Z3RLc21uT09kcVRlc2JwMGJzOHNuNldxczBDOWRnY3hSSHVPTVoydG04bnBMVW03YXJnT1N6UT09IjsKCSJwdXJjaGFzZS1pbmZvIiA9ICJld29KSW05eWFXZHBibUZzTFhCMWNtTm9ZWE5sTFdSaGRHVXRjSE4wSWlBOUlDSXlNREV5TFRBMExUTXdJREE0T2pBMU9qVTFJRUZ0WlhKcFkyRXZURzl6WDBGdVoyVnNaWE1pT3dvSkltOXlhV2RwYm1Gc0xYUnlZVzV6WVdOMGFXOXVMV2xrSWlBOUlDSXhNREF3TURBd01EUTJNVGM0T0RFM0lqc0tDU0ppZG5KeklpQTlJQ0l5TURFeU1EUXlOeUk3Q2draWRISmhibk5oWTNScGIyNHRhV1FpSUQwZ0lqRXdNREF3TURBd05EWXhOemc0TVRjaU93b0pJbkYxWVc1MGFYUjVJaUE5SUNJeElqc0tDU0p2Y21sbmFXNWhiQzF3ZFhKamFHRnpaUzFrWVhSbExXMXpJaUE5SUNJeE16TTFOems0TXpVMU9EWTRJanNLQ1NKd2NtOWtkV04wTFdsa0lpQTlJQ0pqYjIwdWJXbHVaRzF2WW1Gd2NDNWtiM2R1Ykc5aFpDSTdDZ2tpYVhSbGJTMXBaQ0lnUFNBaU5USXhNVEk1T0RFeUlqc0tDU0ppYVdRaUlEMGdJbU52YlM1dGFXNWtiVzlpWVhCd0xrMXBibVJOYjJJaU93b0pJbkIxY21Ob1lYTmxMV1JoZEdVdGJYTWlJRDBnSWpFek16VTNPVGd6TlRVNE5qZ2lPd29KSW5CMWNtTm9ZWE5sTFdSaGRHVWlJRDBnSWpJd01USXRNRFF0TXpBZ01UVTZNRFU2TlRVZ1JYUmpMMGROVkNJN0Nna2ljSFZ5WTJoaGMyVXRaR0YwWlMxd2MzUWlJRDBnSWpJd01USXRNRFF0TXpBZ01EZzZNRFU2TlRVZ1FXMWxjbWxqWVM5TWIzTmZRVzVuWld4bGN5STdDZ2tpYjNKcFoybHVZV3d0Y0hWeVkyaGhjMlV0WkdGMFpTSWdQU0FpTWpBeE1pMHdOQzB6TUNBeE5Ub3dOVG8xTlNCRmRHTXZSMDFVSWpzS2ZRPT0iOwoJImVudmlyb25tZW50IiA9ICJTYW5kYm94IjsKCSJwb2QiID0gIjEwMCI7Cgkic2lnbmluZy1zdGF0dXMiID0gIjAiOwp9';
        
        try {
            //$response = $validator->setReceiptData($receiptBase64Data)->validate();
            $sharedSecret = ''; // Generated in iTunes Connect's In-App Purchase menu
            $response = $validator->setSharedSecret($sharedSecret)->setReceiptData($receiptBase64Data)->validate(); // use setSharedSecret() if for recurring subscriptions
        } catch (Exception $e) {
            return false;
            //echo 'got error = ' . $e->getMessage() . PHP_EOL;
        }

        if ($response->isValid()) {
            $data['receipt'] = $response->getReceipt();
            foreach ($response->getPurchases() as $purchase) {
                $data['transaction_id'] = $purchase->getTransactionId();
                $data['subscriptionId'] = $purchase->getOriginalTransactionId();
                $data['product_id'] = $purchase->getProductId();

                if ($purchase->getPurchaseDate() != null) {
                    $data['date'] = $purchase->getPurchaseDate()->toIso8601String();
                    $data['formated_date'] = date('Y-m-d',strtotime($purchase->getPurchaseDate()->toIso8601String()));
                }
            }
            return $data;
        } else {
            return false;
            /*echo 'Receipt is not valid.' . PHP_EOL;
            echo 'Receipt result code = ' . $response->getResultCode() . PHP_EOL;*/
        }
        

    }
}
?>
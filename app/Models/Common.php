<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Common extends Model
{
    use HasFactory;

    // Get Random Number
    public function random_string($length) {
        $key = '';
        $keys = array_merge(range(0, 9));
        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
            //$key .= '1';
        }
        return $key;
    }

    //for generate token
    public function getToken($length, $config = []) {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= isset($config['notSmall']) && $config['notSmall'] ? '' : "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet); // edited
        for ($i = 0; $i < $length; $i++) {
            $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max - 1)];
        }
        return $token;
    }

    //Start of getNotification function
    public function GetNotification($key, $lang = '1') {
        die('Common Model');
        $colName = "value_en";
        if ($lang == '1') {
            $colName = "value_en";
        }
        $this->db->select('*');
        $this->db->from('tbl_apiresponse');
        $this->db->where("key", $key);
        $this->db->where("status", "1");
        $result = $this->db->get()->row_array();
        if (empty($result)) {
            return $key;
            return "Message not found";
        }
        return $result[$colName];
    }

    public function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 1) {
            return $min; // not so random...
        }
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);

        return $min + $rnd;
    }
    
    //for get file extantion
    public function getFileExtension($file_name) {
        return '.' . substr(strrchr($file_name, '.'), 1);
    }

    public function get_time_ago($time) {
        // print_r($time);
        $time_difference = time() - (int)$time;
        // print_r($time_difference); die;
        if ($time_difference < 1) {
            return '1 second ago';
        }
        $condition = array(12 * 30 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60 => 'month',
            24 * 60 * 60 => 'day',
            60 * 60 => 'hour',
            60 => 'minute',
            1 => 'second'
        );
        foreach ($condition as $secs => $str) {
            $d = $time_difference / $secs;
            if ($d >= 1) {
                $t = round($d);
                return $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
            }
        }
    }

    public function getRandomCode(){ 
        return rand('1111','9999');
    }

    
}

<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    // use HasFactory;

    public static $tbl_notification = 'tbl_notification';
    public static $tbl_users = 'tbl_users';    

    public function getData($data = [], $single = false, $num_rows = false){
        $senderImage = env('APP_URL').env('UPLOAD_URL');
        $thumbSenderImage = env('APP_URL').env('THUMBURL');
        $query = DB::table(self::$tbl_notification);
        if ($num_rows) {
            $query->select(
                DB::raw("COUNT(".self::$tbl_notification.".id) as totalRecord")
            );
        } else {
            $query->select([
                self::$tbl_notification. ".*",
            ]);
        }

        if(isset($data['checkNotification']) && !empty($data['checkNotification'])) {
            DB::table(self::$tbl_notification)->where(['send_from' => $data['checkNotification']['send_from']] OR ['send_from' => $data['checkNotification']['send_to']]);
            
            DB::table(self::$tbl_notification)->where(['send_to' => $data['checkNotification']['send_from']] OR ['send_to' => $data['checkNotification']['send_to']]);
        }
        
        if(isset($data['userData']) && $data['userData'] == true ) {
            // die('kkkkkkkkk');
            $query->select([
                User::select(DB::raw("CONCAT('firstName', ' ', 'lastName') as senderName")),
                // DB::raw("CONCAT(u.firstName, ' ', u.lastName) as senderName"),
                DB::raw("u.role as senderRole"),
                DB::raw("CONCAT($senderImage, ".self::$tbl_users.".image) as senderImage", FALSE),
                DB::raw("CONCAT($thumbSenderImage, ".self::$tbl_users.".image) as thumbSenderImage", FALSE),
            ]);
        }
        $query->join(self::$tbl_users . ' as u', self::$tbl_notification. ".send_from", "=","u.id", "left");
        $query->Where('u.status','1');

        if (isset($data['id']) && !empty($data['id'])) {
            if (is_array($data['id'])) {
                $query->whereIn(self::$tbl_notification. '.id', $data['id']);
            } else {
                $query->Where(self::$tbl_notification. '.id', $data['id']);
            }
        }

        if (isset($data['title'])) {
            $query->where(self::$tbl_notification. '.title', $data['title']);
        }

        if (isset($data['desc'])) {
            $query->where(self::$tbl_notification. '.desc', $data['desc']);
        }

        if (isset($data['send_to'])) {
            $query->where(self::$tbl_notification. '.send_to', $data['send_to']);
        }

        if (isset($data['send_from'])) {
            $query->where(self::$tbl_notification. '.send_from', $data['send_from']);
        }

        if (isset($data['model'])) {
            $query->where(self::$tbl_notification. '.model', $data['model']);
        }

        if (isset($data['model_id'])) {
            $query->where(self::$tbl_notification. '.model_id', $data['model_id']);
        }
        
        if (isset($data['updatedDate'])) {
            $query->where(self::$tbl_notification. '.updatedDate', $data['updatedDate']);
        }

        if (isset($data['createdDate'])) {
            $query->where(self::$tbl_notification. '.createdDate', $data['createdDate']);
        }

        if (isset($data['status'])) {
            if (is_array($data['status'])) {
                $query->whereIn(self::$tbl_notification . '.status', $data['status']);
            } else {
                $query->where(self::$tbl_notification . '.status', $data['status']);
            }
        }
        
        if (!$num_rows) {
            if (isset($data['limit']) && isset($data['offset'])) {
                $query->limit($data['limit']);
                $query->offset($data['offset']);
            } elseif (isset($data['limit']) && !empty($data['limit'])) {
                $query->limit($data['limit']);
            } else {
                $query->limit(10);
            }
        }

        if (isset($data['orderby']) && !empty($data['orderby'])) {
			$query->orderBy(self::$tbl_notification. '.'.$data['orderby'], (isset($data['orderstate']) && !empty($data['orderstate']) ? $data['orderstate'] : 'DESC'));
		} else {
			$query->orderBy(self::$tbl_notification. '.createdDate', 'DESC');
		}

        if ($num_rows) {
			$row = $query->first();
            return isset($row->totalRecord)?$row->totalRecord:"0";
		}

		if ($single) {
			return $query->first();
		} elseif (isset($data['id']) && !empty($data['id']) && !is_array($data['id'])) {
            return $query->first();
        }
		
		return $query->get()->toArray();
    }

    public function setData($data, $id = 0){
        if (empty($data)) {
            return false;
        }
        $modelData = array();
        
        if (isset($data['title'])) {
            $modelData['title'] = $data['title'];
        }

        if (isset($data['desc'])) {
            $modelData['desc'] = $data['desc'];
        }

        if (isset($data['send_to'])) {
            $modelData['send_to'] = $data['send_to'];
        }

        if (isset($data['send_from'])) {
            $modelData['send_from'] = $data['send_from'];
        }

        if (isset($data['model'])) {
            $modelData['model'] = $data['model'];
        }

        if (isset($data['model_id'])) {
            $modelData['model_id'] = $data['model_id'];
        }

        if (isset($data['status'])) {
            $modelData['status'] = $data['status'];
        }

        $modelData['updatedDate'] = time();
        
        if(empty($id)){
            $modelData['createdDate'] = isset($data['createdDate']) && !empty($data['createdDate']) ? $data['createdDate'] : time();
        }

        if (empty($modelData)) {
            return false;
        }
        

        $query = DB::table(self::$tbl_notification);
        if (!empty($id)) {
			if(is_array($id)){
				$query->whereIn('id', $id);
				$query->update($modelData);
			}else{
				$query->where('id', $id);
				$query->update($modelData);
			}
		} else {
			$id = $query->insertGetId($modelData);
		}

        return $id;
    }

    // public function makeread($userId){
    //     self::$tbl_notification->where('send_to', $userId);
    //     $query->update(['status'=>'0']);
    //     return true;
    // }

}

<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Auth extends Model
{
    // use HasFactory;
    public static $tbl = 'tbl_auth';
    protected $table = 'tbl_auth';

    public function getData($data = [], $single = false, $num_rows = false)
    {
        $query = DB::table(self::$tbl);
        if ($num_rows) {
			$query->select(
				DB::raw("COUNT(".self::$tbl.".id) as totalRecord")
			);
		} else {
            $query->select(self::$tbl.".*");
        }

        if (isset($data['id']) && !empty($data['id'])) {
            if (is_array($data['id'])) {
                $query->whereIn(self::$tbl. '.id', $data['id']);
            } else {
                $query->where(self::$tbl. '.id', $data['id']);
            }
        }

        if (isset($data['userId'])) {
            $query->Where(self::$tbl. '.userId', $data['userId']);
        }
        

        if (isset($data['token'])) {
            $query->Where(self::$tbl. '.token', $data['token']);
        }

        if (isset($data['auth_provider'])) {
            $query->Where(self::$tbl. '.auth_provider', $data['auth_provider']);
        }
        
        if (isset($data['auth_id'])) {
            $query->Where(self::$tbl. '.auth_id', $data['auth_id']);
        }
        
        if (isset($data['deviceToken'])) {
            $query->Where(self::$tbl. '.deviceToken', $data['deviceToken']);
        }
        
        if (isset($data['deviceId'])) {
            $query->Where(self::$tbl. '.deviceId', $data['deviceId']);
        }
        
        if (isset($data['deviceType'])) {
            $query->Where(self::$tbl. '.deviceType', $data['deviceType']);
        }

        if (!$num_rows) {
			if (isset($data['length']) && isset($data['start'])) {
				$query->limit($data['length']);
				$query->offset($data['start']);
			} elseif (isset($data['length']) && !empty($data['length'])) {
				$query->limit($data['length']);
			} else {
				$query->limit(10);
			}
		}

        if (isset($data['orderby']) && !empty($data['orderby'])) {
			$query->orderBy(self::$tbl. '.'.$data['orderby'], (isset($data['orderstate']) && !empty($data['orderstate']) ? $data['orderstate'] : 'DESC'));
		} else {
			$query->orderBy(self::$tbl. '.id', 'DESC');
		}

        if ($num_rows) {
			$row = $query->first();
            return isset($row->totalRecord)?$row->totalRecord:"0";
		}

		if ($single) {
			return $query->first();
		}
		return $query->get()->toArray();
    }

    public function setData($data, $id = 0) {
        if (empty($data)) {
            return false;
        }
        $modelData = array();

        if (isset($data['userId'])) {
            $modelData['userId'] = $data['userId'];
        }
        
        if (isset($data['token'])) {
            $modelData['token'] = $data['token'];
        }

        if (isset($data['auth_provider'])) {
            $modelData['auth_provider'] = $data['auth_provider'];
        }

        if (isset($data['auth_id'])) {
            $modelData['auth_id'] = $data['auth_id'];
        }

        if (isset($data['deviceToken'])) {
            $modelData['deviceToken'] = $data['deviceToken'];
        }

        if (isset($data['deviceId'])) {
            $modelData['deviceId'] = $data['deviceId'];
        }

        if (isset($data['deviceType'])) {
            $modelData['deviceType'] = $data['deviceType'];
        }

        if (isset($data['status'])) {
            $modelData['status'] = $data['status'];
        }

        if (empty($modelData)) {
            return false;
        }
        
        if(empty($id)){
            $modelData['createdDate'] = isset($data['createdDate']) && !empty($data['createdDate']) ? $data['createdDate'] : time();
        }

        $modelData['updatedDate'] = time();

        $query = DB::table(self::$tbl);
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

    public function removeToken($token)
    {
        $query = DB::table(self::$tbl)->where(['token' => $token])->delete();
    }
}

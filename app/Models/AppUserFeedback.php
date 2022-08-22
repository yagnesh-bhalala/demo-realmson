<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AppUserFeedback extends Model
{
    // use HasFactory;

    public static $tbl = 'tbl_app_feedback';
    
    public static $tbl_users = 'tbl_users';
    

    public function getData($data = [], $single = false, $num_rows = false){
        $query = DB::table(self::$tbl);
        if ($num_rows) {
			$query->select(
				DB::raw("COUNT(".self::$tbl.".id) as totalRecord")
			);
		} else {
            $query->select([
                self::$tbl. ".*",
                DB::raw("FROM_UNIXTIME(".self::$tbl.".createdDate, '%d-%m-%Y %H:%i') as createdDate"),
                DB::raw("FROM_UNIXTIME(".self::$tbl.".updatedDate, '%d-%m-%Y %H:%i') as updatedDate"),
                DB::raw("CONCAT(u.firstName, ' ', u.lastName) as name"),
            ]);
        }

        $query->join(self::$tbl_users . ' as u', self::$tbl. ".userId", "=","u.id", "left");
        $query->Where('u.status','1');

        if (isset($data['id']) && !empty($data['id'])) {
			if (is_array($data['id'])) {
				$query->whereIn(self::$tbl. '.id', $data['id']);
			} else {
				$query->Where(self::$tbl. '.id', $data['id']);
			}
        }

        if (isset($data['search']) && !empty($data['search'])) {
            $search = trim($data['search']);
            $query->where(function($qu) use ($search){
				$qu->orWhere(self::$tbl_users. '.firstName', 'like', '%'.$search.'%');
				$qu->orWhere(self::$tbl. '.feedback', 'like', '%'.$search.'%');
			});
        }

        if (isset($data['userId'])) {
            $query->where(self::$tbl. '.userId', $data['userId']);
        }

        if (isset($data['rating'])) {
            $query->where(self::$tbl. '.rating', $data['rating']);
        }

        if (isset($data['feedback'])) {
            $query->where(self::$tbl. '.feedback', $data['feedback']);
        }

        if (isset($data['status'])) {
            if (is_array($data['status'])) {
                $query->whereIn(self::$tbl . '.status', $data['status']);
            } else {
                $query->where(self::$tbl . '.status', $data['status']);
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
		} elseif (isset($data['id']) && !empty($data['id']) && !is_array($data['id'])) {
            return $query->first();
        }
		
		return $query->get();
    }

    public function setData($data, $id = 0){
        if (empty($data)) {
            return false;
        }
        $modelData = array();

        if (isset($data['userId'])) {
            $modelData['userId'] = $data['userId'];
        }
        
        if (isset($data['rating'])) {
            $modelData['rating'] = $data['rating'];
        }

        if (isset($data['feedback'])) {
            $modelData['feedback'] = $data['feedback'];
        }

        if (isset($data['status'])) {
            $modelData['status'] = $data['status'];
        }

        $modelData['updatedDate'] = time();

        if (empty($modelData)) {
            return false;
        }
        
        if(empty($id)){
            $modelData['createdDate'] = isset($data['createdDate']) && !empty($data['createdDate']) ? $data['createdDate'] : time();
        }

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

}

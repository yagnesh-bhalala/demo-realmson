<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserSocialAuth extends Model
{
    public static $tbl = 'tbl_userssocialauth';
    protected $fillable = [
        'password',
    ];
    // public static $tbl_user = 'tbl_auth';
    public function get($data = [], $single = false, $num_rows = false) {
        $query = DB::table(self::$tbl);
        if ($num_rows) {
            $query->select('COUNT(' . self::$tbl . '.id) as totalRecord');
        } else {
            $query->select([
                self::$tbl . '.*',
                DB::raw('FROM_UNIXTIME('.self::$tbl . '.createdDate, "%d-%m-%Y %H:%i") as createdDate'),
            ]);
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

        if (isset($data['auth_provider'])) {
            $query->Where(self::$tbl. '.auth_provider', $data['auth_provider']);
        }
        
        if (isset($data['auth_id'])) {
            $query->Where(self::$tbl. '.auth_id', $data['auth_id']);
        }

        if (isset($data['status']) && !empty($data['status'])) {
            if (is_array($data['status'])) {
                $query->whereIn(self::$tbl. '.status', $data['status']);
            } else {
                $query->where(self::$tbl. '.status', $data['status']);
            }
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

        return $query->get();
    }

    public function setData($data, $id = 0) {
        if (empty($data)) {
            return false;
        }
        $modelData = [];

        if (isset($data['userId'])) {
            $modelData['userId'] = $data['userId'];
        }
        
        if (isset($data['auth_provider'])) {
            $modelData['auth_provider'] = $data['auth_provider'];
        }

        if (isset($data['auth_id'])) {
            $modelData['auth_id'] = $data['auth_id'];
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
}

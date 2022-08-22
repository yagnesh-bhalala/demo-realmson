<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Payee extends Model
{
    // use HasFactory;
    public static $tbl = 'tbl_payee';
    protected $table = 'tbl_payee';

    public function getData($data = [], $single = false, $num_rows = false)
    {
        $query = DB::table(self::$tbl);
        if ($num_rows) {
			$query->select(
				DB::raw("COUNT(".self::$tbl.".id) as totalRecord")
			);
		} else {
            $select = [
                self::$tbl.".*",
                DB::raw("FROM_UNIXTIME(".self::$tbl.".createdDate, '%D') as dateDay"),
                DB::raw("FROM_UNIXTIME(".self::$tbl.".createdDate, '%b') as dateMonth"),
                DB::raw("FROM_UNIXTIME(".self::$tbl.".createdDate, '%d-%m-%Y') as createDateFormat"),
                DB::raw("FROM_UNIXTIME(".self::$tbl.".updatedDate, '%d-%m-%Y') as updateDateFormat"),
            ];
            $query->select($select);
        }

        if (isset($data['id']) && !empty($data['id'])) {
            if (is_array($data['id'])) {
                $query->whereIn(self::$tbl. '.id', $data['id']);
            } else {
                $query->where(self::$tbl. '.id', $data['id']);
            }
        }

        if (isset($data['userId'])) {
            $query->where(self::$tbl. '.userId', $data['userId']);
        }

        if (isset($data['mainCategoryId'])) {
            $query->Where(self::$tbl. '.mainCategoryId', $data['mainCategoryId']);
        }

        if (isset($data['payeeName'])) {
            $query->Where(self::$tbl. '.payeeName', $data['payeeName']);
        }

        if (isset($data['payeeType'])) {
            if (is_array($data['payeeType'])) {
                $query->whereIn(self::$tbl . '.payeeType', $data['payeeType']);
            } else {
                $query->where(self::$tbl . '.payeeType', $data['payeeType']);
            }
        }

        if (isset($data['recurringDateDays'])) {
            $query->Where(self::$tbl. '.recurringDateDays', $data['recurringDateDays']);
        }

        if (isset($data['fromCron'])) {
            if (is_array($data['fromCron'])) {
                $query->whereIn(self::$tbl . '.fromCron', $data['fromCron']);
            } else {
                $query->where(self::$tbl . '.fromCron', $data['fromCron']);
            }
        }

        if (isset($data['payeeDate'])) {
            $query->Where(self::$tbl. '.payeeDate', $data['payeeDate']);
        }

        if (isset($data['payeeAmount'])) {
            $query->Where(self::$tbl. '.payeeAmount', $data['payeeAmount']);
        }

        if (isset($data['autoDraft'])) {
            $query->Where(self::$tbl. '.autoDraft', $data['autoDraft']);
        }

        if (isset($data['parentPayeeId'])) {
            $query->where(self::$tbl. '.parentPayeeId', $data['parentPayeeId']);
        }

        if (isset($data['updatedDate'])) {
            $query->where(self::$tbl. '.updatedDate', $data['updatedDate']);
        }

        if (isset($data['createdDate'])) {
            $query->where(self::$tbl. '.createdDate', $data['createdDate']);
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
                // $query->limit(10);
            }
        }

        if (isset($data['orderby']) && !empty($data['orderby'])) {
            $query->orderBy(self::$tbl. '.'.$data['orderby'], (isset($data['orderstate']) && !empty($data['orderstate']) ? $data['orderstate'] : 'DESC'));
        } else {
            $query->orderBy(self::$tbl. '.createdDate', 'DESC');
        }

        if ($num_rows) {
            $row = $query->first();
            return isset($row->totalRecord)?$row->totalRecord:"0";
        }

        if ($single) {
            return $query->first();
        }
        // return $query->toSql();
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

        if (isset($data['mainCategoryId'])) {
            $modelData['mainCategoryId'] = $data['mainCategoryId'];
        }

        if (isset($data['payeeName'])) {
            $modelData['payeeName'] = $data['payeeName'];
        }

        if (isset($data['payeeType'])) {
            $modelData['payeeType'] = $data['payeeType'];
        }

        if (isset($data['recurringDateDays'])) {
            $modelData['recurringDateDays'] = $data['recurringDateDays'];
        }

        if (isset($data['fromCron'])) {
            $modelData['fromCron'] = $data['fromCron'];
        }

        if (isset($data['payeeDate'])) {
            $modelData['payeeDate'] = $data['payeeDate'];
        }

        if (isset($data['payeeAmount'])) {
            $modelData['payeeAmount'] = $data['payeeAmount'];
        }

        if (isset($data['autoDraft'])) {
            $modelData['autoDraft'] = $data['autoDraft'];
        }

        if (isset($data['parentPayeeId'])) {
            $modelData['parentPayeeId'] = $data['parentPayeeId'];
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
            if(is_array($id)) {
                $query->whereIn('id', $id);
                $query->update($modelData);
            } else {
                $query->where('id', $id);
                $query->update($modelData);
            }
        } else {
            $id = $query->insertGetId($modelData);
        }
        return $id;
    }
}

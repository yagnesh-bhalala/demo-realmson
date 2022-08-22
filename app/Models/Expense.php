<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Expense extends Model
{
    // use HasFactory;
    public static $tbl = 'tbl_expense';
    protected $table = 'tbl_expense';
    public static $tbl_main_category = 'tbl_main_category';
    protected $table_main_category = 'tbl_main_category';

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
                DB::raw("FROM_UNIXTIME(".self::$tbl.".createdDate, '%d-%m-%Y') as createdDate"),
                DB::raw("FROM_UNIXTIME(".self::$tbl.".createdDate, '%b %d %Y') as createdSDate"),
                DB::raw("FROM_UNIXTIME(".self::$tbl.".createdDate, '%h:%i%p') as createdSTime"),
                DB::raw("FROM_UNIXTIME(".self::$tbl.".updatedDate, '%d-%m-%Y') as updatedDate"),
                
            ];

            if (isset($data['dashboard'])) {
                $select = [
                    DB::raw("sum(".self::$tbl.".expnsAmount) as totalAmount"),
                    DB::raw("count(".self::$tbl.".id) as totalTransaction"),
                    'mc.mainCategoryName',
                    'mc.colorCode',
                    'mc.id as mainCategoryId',
                    // DB::raw("CONCAT('" . env('APP_URL').Config('constant.UPLOAD_URL') . "', mc.image) as categoryMainImage"),
                    DB::raw("CONCAT('" . env('APP_URL').env('THUMBURL') . "', mc.image) as categoryThumbImage"),
                ];
            }
            $query->select($select);
        }

        if (isset($data['dashboard'])) {
            $query->join(self::$tbl_main_category . ' as mc', self::$tbl. ".mainCategoryId", "=","mc.id", "left");
            $query->whereNotNull(self::$tbl. '.mainCategoryId');
            $query->groupBy(self::$tbl. '.mainCategoryId');
        }

        if (isset($data['id']) && !empty($data['id'])) {
            if (is_array($data['id'])) {
                $query->whereIn(self::$tbl. '.id', $data['id']);
            } else {
                $query->where(self::$tbl. '.id', $data['id']);
            }
        }

        if (isset($data['transactionId'])) {
            $query->where(self::$tbl. '.transactionId', $data['transactionId']);
        }

        if (isset($data['accountId'])) {
            $query->where(self::$tbl. '.accountId', $data['accountId']);
        }

        if (isset($data['userId'])) {
            $query->where(self::$tbl. '.userId', $data['userId']);
        }

        if (isset($data['mainCategoryId'])) {
            $query->Where(self::$tbl. '.mainCategoryId', $data['mainCategoryId']);
        }

        if (isset($data['plaidCategoryId'])) {
            $query->Where(self::$tbl. '.plaidCategoryId', $data['plaidCategoryId']);
        }

        if (isset($data['transactionName'])) {
            $query->Where(self::$tbl. '.transactionName', $data['transactionName']);
        }

        if (isset($data['expnsType'])) {
            if (is_array($data['expnsType'])) {
                $query->whereIn(self::$tbl . '.expnsType', $data['expnsType']);
            } else {
                $query->where(self::$tbl . '.expnsType', $data['expnsType']);
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

        if (isset($data['expnsDate'])) {
            $query->Where(self::$tbl. '.expnsDate', $data['expnsDate']);
        }

        if (isset($data['expnsAmount'])) {
            $query->Where(self::$tbl. '.expnsAmount', $data['expnsAmount']);
        }

        if (isset($data['parentExpenseId'])) {
            $modelData['parentExpenseId'] = $data['parentExpenseId'];
        }

        if (isset($data['expnsNote'])) {
            $modelData['expnsNote'] = $data['expnsNote'];
        }

        if (isset($data['expnsStatus'])) {
            if (is_array($data['expnsStatus'])) {
                $query->whereIn(self::$tbl . '.expnsStatus', $data['expnsStatus']);
            } else {
                $query->where(self::$tbl . '.expnsStatus', $data['expnsStatus']);
            }
        }

        if (isset($data['updatedDate'])) {
            $query->where(self::$tbl. '.updatedDate', $data['updatedDate']);
        }

        if (isset($data['createdDate'])) {
            $query->where(self::$tbl. '.createdDate', $data['createdDate']);
        }

        if (isset($data['dateRangeStart'])) {
            // die('hh');
            $query->whereBetween(DB::raw("FROM_UNIXTIME(".self::$tbl.".createdDate, '%Y-%m-%d')")  , [$data['dateRangeStart'], $data['dateRangeEnd']]);
        }

        // if (isset($data['upcomingDateRangeStart'])) {
        //     // die('hh');
        //     $query->whereBetween(self::$tbl.".recurringDateDays"  , [$data['upcomingDateRangeStart'], $data['upcomingDateRangeEnd']]);
        // }

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

        if (isset($data['transactionId'])) {
            $modelData['transactionId'] = $data['transactionId'];
        }

        if (isset($data['accountId'])) {
            $modelData['accountId'] = $data['accountId'];
        }

        if (isset($data['userId'])) {
            $modelData['userId'] = $data['userId'];
        }

        if (isset($data['mainCategoryId'])) {
            $modelData['mainCategoryId'] = $data['mainCategoryId'];
        }

        if (isset($data['plaidCategoryId'])) {
            $modelData['plaidCategoryId'] = $data['plaidCategoryId'];
        }

        if (isset($data['transactionName'])) {
            $modelData['transactionName'] = $data['transactionName'];
        }

        if (isset($data['expnsType'])) {
            $modelData['expnsType'] = $data['expnsType'];
        }

        if (isset($data['recurringDateDays'])) {
            $modelData['recurringDateDays'] = $data['recurringDateDays'];
        }

        if (isset($data['fromCron'])) {
            $modelData['fromCron'] = $data['fromCron'];
        }

        if (isset($data['expnsDate'])) {
            $modelData['expnsDate'] = $data['expnsDate'];
        }

        if (isset($data['expnsAmount'])) {
            $modelData['expnsAmount'] = $data['expnsAmount'];
        }

        if (isset($data['parentExpenseId'])) {
            $modelData['parentExpenseId'] = $data['parentExpenseId'];
        }

        if (isset($data['expnsNote'])) {
            $modelData['expnsNote'] = $data['expnsNote'];
        }

        if (isset($data['expnsStatus'])) {
            $modelData['expnsStatus'] = $data['expnsStatus'];
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

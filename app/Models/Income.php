<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Income extends Model
{
    // use HasFactory;
    public static $tbl = 'tbl_income';
    protected $table = 'tbl_income';

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
                DB::raw("FROM_UNIXTIME(".self::$tbl.".updatedDate, '%d-%m-%Y') as updatedDate"),
                // DB::raw("SUM(".self::$tbl.".incomeAmount) as totalIncomeForThisMonth"),
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

        if (isset($data['incomeName'])) {
            $query->Where(self::$tbl. '.incomeName', $data['incomeName']);
        }

        if (isset($data['incomeType'])) {
            if (is_array($data['incomeType'])) {
                $query->whereIn(self::$tbl . '.incomeType', $data['incomeType']);
            } else {
                $query->where(self::$tbl . '.incomeType', $data['incomeType']);
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

        // if (isset($data['fromCron'])) {
        //     $query->Where(self::$tbl. '.fromCron', $data['fromCron']);
        // }

        if (isset($data['incomeDate'])) {
            $query->Where(self::$tbl. '.incomeDate', $data['incomeDate']);
        }

        if (isset($data['incomeAmount'])) {
            $query->Where(self::$tbl. '.incomeAmount', $data['incomeAmount']);
        }

        if (isset($data['parentIncomeId'])) {
            $query->where(self::$tbl. '.parentIncomeId', $data['parentIncomeId']);
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
			$query->orderBy(self::$tbl. '.createdDate', 'DESC');
		}

        if ($num_rows) {
			$row = $query->first();
            return isset($row->totalRecord)?$row->totalRecord:"0";
		}

		if ($single) {
			return $query->first();
		}
		return $query->get()->toArray();
		// return $query->toSql();
    }

    public function setData($data, $id = 0) {
        if (empty($data)) {
            return false;
        }
        $modelData = array();

        if (isset($data['userId'])) {
            $modelData['userId'] = $data['userId'];
        }

        if (isset($data['incomeName'])) {
            $modelData['incomeName'] = $data['incomeName'];
        }

        if (isset($data['incomeType'])) {
            $modelData['incomeType'] = $data['incomeType'];
        }

        if (isset($data['recurringDateDays'])) {
            $modelData['recurringDateDays'] = $data['recurringDateDays'];
        }

        if (isset($data['fromCron'])) {
            $modelData['fromCron'] = $data['fromCron'];
        }

        if (isset($data['incomeDate'])) {
            $modelData['incomeDate'] = $data['incomeDate'];
        }

        if (isset($data['incomeAmount'])) {
            $modelData['incomeAmount'] = $data['incomeAmount'];
        }

        if (isset($data['parentIncomeId'])) {
            $modelData['parentIncomeId'] = $data['parentIncomeId'];
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

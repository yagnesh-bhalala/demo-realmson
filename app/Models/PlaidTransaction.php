<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PlaidTransaction extends Model
{
    // use HasFactory;

    public static $tbl = 'tbl_plaid_transaction';
    protected $table = 'tbl_plaid_transaction';

    public function getData($data = [], $single = false, $num_rows = false){
        $query = DB::table(self::$tbl);
        if ($num_rows) {
			$query->select(
				DB::raw("COUNT(".self::$tbl.".id) as totalRecord")
			);
		} else {
            $query->select([
                self::$tbl. ".*",
                DB::raw("FROM_UNIXTIME(".self::$tbl.".createdDate, '%d-%m-%Y %H:%i') as createdDate")
            ]);
        }

        if (isset($data['id']) && !empty($data['id'])) {
			if (is_array($data['id'])) {
				$query->whereIn(self::$tbl. '.id', $data['id']);
			} else {
				$query->Where(self::$tbl. '.id', $data['id']);
			}
        }

        // if (isset($data['search']) && !empty($data['search'])) {
        //     $search = trim($data['search']);
        //     $query->where(function($qu) use ($search){
		// 		$qu->orWhere(self::$tbl. '.expiration', 'like', '%'.$search.'%');
		// 		$qu->orWhere(self::$tbl. '.key', 'like', '%'.$search.'%');
		// 	});
        // }

        if (isset($data['userId'])) {
            $query->where(self::$tbl. '.userId', $data['userId']);
        }

        if (isset($data['account_id'])) {
            $query->where(self::$tbl. '.account_id', $data['account_id']);
        }

        if (isset($data['main_category_id'])) {
            $query->where(self::$tbl. '.main_category_id', $data['main_category_id']);
        }

        if (isset($data['plaid_category_id'])) {
            $query->where(self::$tbl. '.plaid_category_id', $data['plaid_category_id']);
        }

        if (isset($data['transaction_date'])) {
            $query->where(self::$tbl. '.transaction_date', $data['transaction_date']);
        }

        if (isset($data['transaction_name'])) {
            $query->where(self::$tbl. '.transaction_name', $data['transaction_name']);
        }

        if (isset($data['transaction_id'])) {
            $query->where(self::$tbl. '.transaction_id', $data['transaction_id']);
        }

        if (isset($data['payment_meta'])) {
            $query->where(self::$tbl. '.payment_meta', $data['payment_meta']);
        }

        if (isset($data['amount'])) {
            $query->where(self::$tbl. '.amount', $data['amount']);
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
		
		return $query->get()->toArray();
    }

    public function setData($data, $id = 0){
        if (empty($data)) {
            return false;
        }
        $modelData = array();


        if (isset($data['userId'])) {
            $modelData['userId'] = $data['userId'];
        }

        if (isset($data['account_id'])) {
            $modelData['account_id'] = $data['account_id'];
        }
        
        if (isset($data['main_category_id'])) {
            $modelData['main_category_id'] = $data['main_category_id'];
        }

        if (isset($data['plaid_category_id'])) {
            $modelData['plaid_category_id'] = $data['plaid_category_id'];
        }

        if (isset($data['transaction_date'])) {
            $modelData['transaction_date'] = $data['transaction_date'];
        }

        if (isset($data['transaction_name'])) {
            $modelData['transaction_name'] = $data['transaction_name'];
        }

        if (isset($data['transaction_id'])) {
            $modelData['transaction_id'] = $data['transaction_id'];
        }

        if (isset($data['payment_meta'])) {
            $modelData['payment_meta'] = $data['payment_meta'];
        }

        if (isset($data['amount'])) {
            $modelData['amount'] = $data['amount'];
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

<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PlaidCategory extends Model
{
    // use HasFactory;
    public static $tbl = 'tbl_plaid_category';
    protected $table = 'tbl_plaid_category';

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

        if (isset($data['search']) && !empty($data['search'])) {
            $search = trim($data['search']);
            $query->where(function($qu) use ($search) {
				$qu->orWhere(self::$tbl. '.plaidGroup', 'like', '%'.$search.'%');
				$qu->orWhere(self::$tbl. '.plaidHierarchy', 'like', '%'.$search.'%');
			});
        }

        if (isset($data['id']) && !empty($data['id'])) {
            if (is_array($data['id'])) {
                $query->whereIn(self::$tbl. '.id', $data['id']);
            } else {
                $query->where(self::$tbl. '.id', $data['id']);
            }
        }

        if (isset($data['plaidCategoryId']) && !empty($data['plaidCategoryId'])) {
            if (is_array($data['plaidCategoryId'])) {
                $query->whereIn(self::$tbl. '.plaidCategoryId', $data['plaidCategoryId']);
            } else {
                $query->where(self::$tbl. '.plaidCategoryId', $data['plaidCategoryId']);
            }
        }

        if (isset($data['categoryId']) && !empty($data['categoryId'])) {
            if (is_array($data['categoryId'])) {
                $query->whereIn(self::$tbl. '.categoryId', $data['categoryId']);
            } else {
                $query->where(self::$tbl. '.categoryId', $data['categoryId']);
            }
        }

        if (isset($data['plaidGroup'])) {
            $query->Where(self::$tbl. '.plaidGroup', $data['plaidGroup']);
        }

        if (isset($data['plaidHierarchy'])) {
            $query->Where(self::$tbl. '.plaidHierarchy', $data['plaidHierarchy']);
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
    }

    public function setData($data, $id = 0) {
        if (empty($data)) {
            return false;
        }
        $modelData = array();

        if (isset($data['plaidCategoryId'])) {
            $modelData['plaidCategoryId'] = $data['plaidCategoryId'];
        }

        if (isset($data['categoryId'])) {
            $modelData['categoryId'] = $data['categoryId'];
        }

        if (isset($data['plaidGroup'])) {
            $modelData['plaidGroup'] = $data['plaidGroup'];
        }

        if (isset($data['plaidHierarchy'])) {
            $modelData['plaidHierarchy'] = $data['plaidHierarchy'];
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

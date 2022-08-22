<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Blog extends Model
{
    public static $tbl = 'tbl_blog';
    public function getData($data = [], $single = false, $num_rows = false) {
        $query = DB::table(self::$tbl);
        if ($num_rows) {
			$query->select(
				DB::raw("COUNT(".self::$tbl.".id) as totalRecord")
			);
		} else {
            $base = env('APP_URL').Config('constant.UPLOAD_URL');
            $query->select([
                self::$tbl. ".*",
                DB::raw('if(' . self::$tbl . '.updatedDate=0,\'\',FROM_UNIXTIME(' . self::$tbl . '.updatedDate, \'%b %d,%Y\')) as updatedDate'),
                DB::raw("FROM_UNIXTIME(".self::$tbl.".createdDate, '%b %d %Y') as createdDate"),
                DB::raw("CONCAT('" . $base . "', " . self::$tbl . ".image) as image", FALSE),
                DB::raw("CONCAT('" . env('APP_URL').env('THUMBURL') . "', " . self::$tbl . ".image) as thumbImage", FALSE),

            ]);
        }

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
				$qu->orWhere(self::$tbl. '.title', 'like', '%'.$search.'%');
			});
        }

        if (isset($data['title'])) {
            $query->where(self::$tbl. '.title', $data['title']);
        }

        if (isset($data['slug'])) {
            $query->where(self::$tbl. '.slug', $data['slug']);
        }

        if (isset($data['description'])) {
            $query->where(self::$tbl. '.description', $data['description']);
        }

        if (isset($data['metatitle'])) {
            $query->where(self::$tbl. '.metatitle', $data['metatitle']);
        }

        if (isset($data['metakeyword'])) {
            $query->where(self::$tbl. '.metakeyword', $data['metakeyword']);
        }

        if (isset($data['metadescription'])) {
            $query->where(self::$tbl. '.metadescription', $data['metadescription']);
        }

        if (isset($data['image'])) {
            $query->where(self::$tbl. '.image', $data['image']);
        }

        if (isset($data['createdDate'])) {
            $query->where(self::$tbl. '.createdDate', $data['createdDate']);
        }

        if (isset($data['updatedDate'])) {
            $query->where(self::$tbl. '.updatedDate', $data['updatedDate']);
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

    public function setData($data, $id = 0) {
        if (empty($data)) {
            return false;
        }
        $modelData = array();

        if (isset($data['title'])) {
            $modelData['title'] = $data['title'];
        }
        
        if (isset($data['slug'])) {
            $modelData['slug'] = $data['slug'];
        }

        if (isset($data['description'])) {
            $modelData['description'] = $data['description'];
        }

        if (isset($data['metatitle'])) {
            $modelData['metatitle'] = $data['metatitle'];
        }
        
        if (isset($data['metakeyword'])) {
            $modelData['metakeyword'] = $data['metakeyword'];
        }

        if (isset($data['metadescription'])) {
            $modelData['metadescription'] = $data['metadescription'];
        }

        if (isset($data['image'])) {
            $modelData['image'] = ucwords($data['image']);
        }

        if (isset($data['status'])) {
            $modelData['status'] = $data['status'];
        }

        if (isset($data['createdDate'])) {
            $modelData['createdDate'] = $data['createdDate'];
            $modelData['updatedDate'] = $data['createdDate'];
        } else {
            $modelData['updatedDate'] = time();
        }

        if (empty($modelData)) {
            return false;
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

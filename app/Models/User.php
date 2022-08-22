<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use App\Models\Auth as AuthModel;

class User extends Authenticatable
{
    // use Notifiable;
    protected $table = 'tbl_users';
    public static $tbl = 'tbl_users';

    public static function getData($data = [], $single = false, $num_rows = false) {		
        $query = DB::table(self::$tbl);
		if ($num_rows) {
			$query->select(
				DB::raw("COUNT(".self::$tbl.".id) as totalRecord")
			);
		} else {
			if(isset($data['apiResponse'])) {
				$query->select([
					self::$tbl.".firstName",
					self::$tbl.".lastName",
					self::$tbl.".email",
					self::$tbl.".role",
				]);
			} 
			else {
				$base = env('APP_URL').Config('constant.UPLOAD_URL');
				$query->select([
					self::$tbl. ".*",
					self::$tbl. ".status",
					DB::raw("FROM_UNIXTIME(".self::$tbl.".created_at, '%d-%m-%Y %H:%i') as created_at"),
					DB::raw("FROM_UNIXTIME(".self::$tbl.".updated_at, '%d-%m-%Y %H:%i') as updated_at"),
					DB::raw("CONCAT('" . $base . "', " . self::$tbl . ".image) as profileimage"),
					DB::raw("CONCAT('" . env('APP_URL').env('THUMBURL') . "', " . self::$tbl . ".image) as thumbprofileimage"),
				]);
			}
		}
		
		if (isset($data['id']) && !empty($data['id'])) {
			if (is_array($data['id'])) {
				$query->whereIn(self::$tbl. '.id', $data['id']);
			} else {
				$query->Where(self::$tbl. '.id', $data['id']);
			}
        }
		
        if (isset($data['search']) && !empty($data['search'])) {
			$search = $data['search'];
			$query->where(function($qu) use ($search){
				$qu->orWhere(self::$tbl. '.firstName', 'like', '%'.$search.'%');
				$qu->orWhere(self::$tbl. '.lastName', 'like', '%'.$search.'%');
				$qu->orWhere(self::$tbl. '.email', 'like', '%'.$search.'%');
			});
		}

		if (isset($data['like']) && isset($data['value'])) {
            $query->like(self::$tbl . '.' . $data['like'], $data['value']);
        }

		if (isset($data['firstName']) && !empty($data['firstName'])) {
			$query->Where(self::$tbl. '.firstName', $data['firstName']);
		}
		
		if (isset($data['lastName']) && !empty($data['lastName'])) {
			$query->Where(self::$tbl. '.lastName', $data['lastName']);
		}

		if (isset($data['image']) && !empty($data['image'])) {
			$query->Where(self::$tbl. '.image', $data['image']);
		}
		
		if (isset($data['email']) && !empty($data['email'])) {
			$query->Where(self::$tbl. '.email', $data['email']);
		}
		
		if (isset($data['password']) && !empty($data['password'])) {
			$query->Where(self::$tbl. '.password', $data['password']);
        }
		
		if (isset($data['role'])) {
			if (is_array($data['role'])) {
				$query->whereIn(self::$tbl. '.role', $data['role']);
			} else {
				$query->Where(self::$tbl. '.role', $data['role']);
			}
		}

		if (isset($data['forgotCode']) && !empty($data['forgotCode'])) {
			$query->Where(self::$tbl. '.forgotCode', $data['forgotCode']);
        }
		
		if (isset($data['verificationCode']) && !empty($data['verificationCode'])) {
			$query->Where(self::$tbl. '.verificationCode', $data['verificationCode']);
        }

		if (isset($data['isStudent']) && !empty($data['isStudent'])) {
			$query->Where(self::$tbl. '.isStudent', $data['isStudent']);
        }

		if (isset($data['gender']) && !empty($data['gender'])) {
			$query->Where(self::$tbl. '.gender', $data['gender']);
        }

		if (isset($data['parentName']) && !empty($data['parentName'])) {
			$query->Where(self::$tbl. '.parentName', $data['parentName']);
        }

		if (isset($data['parentEmail']) && !empty($data['parentEmail'])) {
			$query->Where(self::$tbl. '.parentEmail', $data['parentEmail']);
        }

		if (isset($data['parentVerification']) && !empty($data['parentVerification'])) {
			$query->Where(self::$tbl. '.parentVerification', $data['parentVerification']);
        }

		if (isset($data['grade']) && !empty($data['grade'])) {
			$query->Where(self::$tbl. '.grade', $data['grade']);
        }

		if (isset($data['graduationYear']) && !empty($data['graduationYear'])) {
			$query->Where(self::$tbl. '.graduationYear', $data['graduationYear']);
        }

		if (isset($data['birthdate']) && !empty($data['birthdate'])) {
			$query->Where(self::$tbl. '.birthdate', $data['birthdate']);
        }

		if (isset($data['state']) && !empty($data['state'])) {
			$query->Where(self::$tbl. '.state', $data['state']);
        }

		if (isset($data['city']) && !empty($data['city'])) {
			$query->Where(self::$tbl. '.city', $data['city']);
        }

		if (isset($data['zipCode']) && !empty($data['zipCode'])) {
			$query->Where(self::$tbl. '.zipCode', $data['zipCode']);
        }

		if (isset($data['annualIncome']) && !empty($data['annualIncome'])) {
			$query->Where(self::$tbl. '.annualIncome', $data['annualIncome']);
        }
		
		if (isset($data['timeZone']) && !empty($data['timeZone'])) {
			$query->Where(self::$tbl. '.timeZone', $data['timeZone']);
        }

		if (isset($data['isOnline']) && !empty($data['isOnline'])) {
            $query->where(self::$tbl . '.isOnline', $data['isOnline']);
        }

        if (isset($data['activeStatus']) && !empty($data['activeStatus'])) {
            $query->where(self::$tbl . '.activeStatus', $data['activeStatus']);
        }

        if (isset($data['profileStatus']) && !empty($data['profileStatus'])) {
            $query->where(self::$tbl . '.profileStatus', $data['profileStatus']);
        }

        if (isset($data['customerId']) && !empty($data['customerId'])) {
            $query->where(self::$tbl . '.customerId', $data['customerId']);
        }
        
        //Common data
		if (isset($data['status'])) {
			if (is_array($data['status'])) {
				$query->whereIn(self::$tbl. '.status', $data['status']);
			} else {
				$query->Where(self::$tbl. '.status', $data['status']);
			}
		}

		if (isset($data['created_at'])) {
			$query->Where(self::$tbl. '.created_at', $data['created_at']);
		}

		if (isset($data['updated_at'])) {
			$query->Where(self::$tbl. '.updated_at', $data['updated_at']);
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
		}
		
		return $query->get()->toArray();
    }

    public static function setData($data, $id = 0) {
		if (empty($data)) {
			return false;
		}
		
		// print_r($data, $id);
		$modelData = array();

		if (isset($data['firstName'])) {
			$modelData['firstName'] = $data['firstName'];
		}

		if (isset($data['lastName'])) {
            $modelData['lastName'] = ucwords($data['lastName']);
        }

        if (isset($data['email'])) {
            $modelData['email'] = $data['email'];
        }

        if (isset($data['password'])) {
            $modelData['password'] = $data['password'];
        }

        if (isset($data['role'])) {
            $modelData['role'] = $data['role'];
        }

        if (isset($data['image']) && !empty($data['image'])) {
            $modelData['image'] = $data['image'];
        }

        if (isset($data['isStudent']) && !empty($data['isStudent'])) {
            $modelData['isStudent'] = $data['isStudent'];
        }
        
        if (isset($data['gender']) && !empty($data['gender'])) {
            $modelData['gender'] = $data['gender'];
        }

        if (isset($data['parentName']) && !empty($data['parentName'])) {
            $modelData['parentName'] = $data['parentName'];
        }

        if (isset($data['parentEmail']) && !empty($data['parentEmail'])) {
            $modelData['parentEmail'] = $data['parentEmail'];
        }

        if (isset($data['parentVerificationCode']) || !empty($data['parentVerificationCode'])) {
            $modelData['parentVerificationCode'] = $data['parentVerificationCode'];
        }

        if (isset($data['grade']) || !empty($data['grade'])) {
            $modelData['grade'] = $data['grade'];
        }

        if (isset($data['graduationYear']) || !empty($data['graduationYear'])) {
            $modelData['graduationYear'] = $data['graduationYear'];
        }

        if (isset($data['birthdate']) && !empty($data['birthdate'])) {
            $modelData['birthdate'] = $data['birthdate'];
        }

        if (isset($data['state']) && !empty($data['state'])) {
            $modelData['state'] = $data['state'];
        }

        if (isset($data['city']) && !empty($data['city'])) {
            $modelData['city'] = $data['city'];
        }

        if (isset($data['zipCode']) && !empty($data['zipCode'])) {
            $modelData['zipCode'] = $data['zipCode'];
        }

        if (isset($data['annualIncome']) && !empty($data['annualIncome'])) {
            $modelData['annualIncome'] = $data['annualIncome'];
        }

        if (isset($data['verificationCode'])) {
            $modelData['verificationCode'] = $data['verificationCode'];
        }
    
        if (isset($data['forgotCode'])) {
            $modelData['forgotCode'] = $data['forgotCode'];
        }

        if (isset($data['timeZone'])) {
            $modelData['timeZone'] = $data['timeZone'];
        }
    
        if (isset($data['isOnline'])) {
            $modelData['isOnline'] = $data['isOnline'];
        }

        if (isset($data['activeStatus'])) {
            $modelData['activeStatus'] = $data['activeStatus'];
        }

        if (isset($data['profileStatus'])) {
            $modelData['profileStatus'] = $data['profileStatus'];
        }

        if (isset($data['customerId'])) {
            $modelData['customerId'] = $data['customerId'];
        }

        if (isset($data['status'])) {
            $modelData['status'] = $data['status'];
        }

        if (isset($data['updated_at'])) {
            $modelData['updated_at'] = $data['updated_at'];
        } elseif (!empty($id)) {
            $modelData['updated_at'] = time();
        }

        if (empty($modelData)) {
            return false;
        }
        if (empty($id)) {
            $modelData['created_at'] = !empty($data['created_at']) ? $data['created_at'] : time();
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

    public static function UserData($id, $secure=FALSE, $authId = "") {
        if (empty($id)) {
            return false;
        }		
		$user = User::getData(['id' => $id], true);

        if (empty($user)) {
            return false;
        }

        if (empty($user->password)) {
            $user->fillpassword = "0";
        } else {
            $user->fillpassword = "1";
        }

        if (empty($user)) {
            return false;
        }

        if ($secure == FALSE) {
            $user->token = "";
        }

        $user->password = "";
        $user->forgotCode = "";
        $user->verificationCode = "";
        $user->token = "";
        
        if(!empty($authId)) {
			$authModel = new AuthModel;
            $getAuthData = $authModel->getData(['id' => $authId],TRUE);
            if(!empty($getAuthData)){
                $user->token = $getAuthData->token;
            }
        }
        return $user;
	}
}

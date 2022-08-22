<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ticket extends Model
{
    // use HasFactory;

    public static $tbl_ticket = 'tbl_ticket';
    public static $tbl_users = 'tbl_users';
    public static $tbl_ticket_reply = 'tbl_ticket_reply';
    

    public function getData($data = [], $single = false, $num_rows = false){
        $query = DB::table(self::$tbl_ticket);
        if ($num_rows) {
			$query->select(
				DB::raw("COUNT(".self::$tbl_ticket.".id) as totalRecord")
			);
		} else {
            $query->select([
                self::$tbl_ticket. ".*",
                DB::raw("FROM_UNIXTIME(".self::$tbl_ticket.".createdDate, '%d-%m-%Y %H:%i') as timeStamp"),
                DB::raw("FROM_UNIXTIME(".self::$tbl_ticket.".createdDate, '%b %d %Y') as createdDate"),
                DB::raw("CONCAT(u.firstName, ' ', u.lastName) as name"),
                DB::raw("u.email"),
            ]);

            if (isset($data['formatedData']) && $data['formatedData']) {
                // $this->db->select("IF(".$this->table.".createdDate < 1,0,DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(" . $this->table . ".createdDate), '" . $_ENV['SYSTEMTIMEZON'] . "', '" . (!empty($data['formatedData']) ? $data['formatedData'] : $_ENV['SYSTEMTIMEZON']) . "'), '%D %b %Y at %l:%i %p')) AS createdDate");
                // $this->db->select("IF(".$this->table.".updatedDate < 1,0,DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(" . $this->table . ".updatedDate), '" . $_ENV['SYSTEMTIMEZON'] . "', '" . (!empty($data['formatedData']) ? $data['formatedData'] : $_ENV['SYSTEMTIMEZON']) . "'), '%D %b %Y at %l:%i %p')) AS updatedDate");
                // $this->db->select("IF(".$this->table.".closedDate < 1,0,DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(" . $this->table . ".closedDate), '" . $_ENV['SYSTEMTIMEZON'] . "', '" . (!empty($data['formatedData']) ? $data['formatedData'] : $_ENV['SYSTEMTIMEZON']) . "'), '%D %b %Y at %l:%i %p')) AS closedDate");
                // $this->db->select("IF(".$this->table.".reopenDate < 1,0,DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(" . $this->table . ".reopenDate), '" . $_ENV['SYSTEMTIMEZON'] . "', '" . (!empty($data['formatedData']) ? $data['formatedData'] : $_ENV['SYSTEMTIMEZON']) . "'), '%D %b %Y at %l:%i %p')) AS reopenDate");
            }
        }

        $query->join(self::$tbl_users . ' as u', self::$tbl_ticket. ".userId", "=","u.id", "left");

        if (isset($data['id']) && !empty($data['id'])) {
			if (is_array($data['id'])) {
				$query->whereIn(self::$tbl_ticket. '.id', $data['id']);
			} else {
				$query->Where(self::$tbl_ticket. '.id', $data['id']);
			}
        }

        if (isset($data['search']) && !empty($data['search'])) {
            $search = trim($data['search']);
            $query->where(function($qu) use ($search){
				$qu->orWhere(self::$tbl_ticket. '.title', 'like', '%'.$search.'%');
				$qu->orWhere(self::$tbl_ticket. '.description', 'like', '%'.$search.'%');
			});
        }

        if (isset($data['userId'])) {
            $query->where(self::$tbl_ticket. '.userId', $data['userId']);
        }

        if (isset($data['title'])) {
            $query->where(self::$tbl_ticket. '.title', $data['title']);
        }

        if (isset($data['description'])) {
            $query->where(self::$tbl_ticket. '.description', $data['description']);
        }

        if (isset($data['priority'])) {
            $query->where(self::$tbl_ticket. '.priority', $data['priority']);
        }

        if (isset($data['closedDate'])) {
            $query->where(self::$tbl_ticket. '.closedDate', $data['closedDate']);
        }

        if (isset($data['reopenDate'])) {
            $query->where(self::$tbl_ticket. '.reopenDate', $data['reopenDate']);
        }

        if (isset($data['updatedDate'])) {
            $query->where(self::$tbl_ticket. '.updatedDate', $data['updatedDate']);
        }

        if (isset($data['createdDate'])) {
            $query->where(self::$tbl_ticket. '.createdDate', $data['createdDate']);
        }

        if (isset($data['status'])) {
            if (is_array($data['status'])) {
                $query->whereIn(self::$tbl_ticket . '.status', $data['status']);
            } else {
                $query->where(self::$tbl_ticket . '.status', $data['status']);
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
			$query->orderBy(self::$tbl_ticket. '.'.$data['orderby'], (isset($data['orderstate']) && !empty($data['orderstate']) ? $data['orderstate'] : 'DESC'));
		} else {
			$query->orderBy(self::$tbl_ticket. '.id', 'DESC');
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
        
        if (isset($data['title'])) {
            $modelData['title'] = $data['title'];
        }

        if (isset($data['description'])) {
            $modelData['description'] = $data['description'];
        }

        if (isset($data['priority'])) {
            $modelData['priority'] = $data['priority'];
        }

        if (isset($data['closedDate'])) {
            $modelData['closedDate'] = $data['closedDate'];
        }

        if (isset($data['reopenDate'])) {
            $modelData['reopenDate'] = $data['reopenDate'];
        }

        if (isset($data['status'])) {
            $modelData['status'] = $data['status'];
        }

        $modelData['updatedDate'] = time();
        
        if(empty($id)){
            $modelData['createdDate'] = isset($data['createdDate']) && !empty($data['createdDate']) ? $data['createdDate'] : time();
        }

        if (empty($modelData)) {
            return false;
        }
        

        $query = DB::table(self::$tbl_ticket);
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

    public function setTicketReplyData($data, $id = 0) {

        if (empty($data)) {
            return false;
        }

        $modelData = array();

        if (isset($data['ticketId'])) {
            $modelData['ticketId'] = $data['ticketId'];
        }

        if (isset($data['description'])) {
            $modelData['description'] = $data['description'];
        }

        if (isset($data['forReply'])) {
            $modelData['forReply'] = $data['forReply'];
        }

        if (isset($data['replyType'])) {
            $modelData['replyType'] = $data['replyType'];
        }

        if (isset($data['status'])) {
            $modelData['status'] = $data['status'];
        }

        if (empty($id)) {
            $modelData['createdDate'] = !empty($data['createdDate']) ? $data['createdDate'] : time();
        }
        if (empty($modelData)) {
            return false;
        }

        $query = DB::table(self::$tbl_ticket_reply);
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

    public function getTicketReply($data = [], $single = false, $num_rows = false) {
        $uploadUrl = env('APP_URL').env('UPLOAD_URL');
        $thumbUrl = env('APP_URL').env('THUMBURL');
        $query = DB::table(self::$tbl_ticket_reply);
        if ($num_rows) {
            $query->select(
                DB::raw("COUNT(".self::$tbl_ticket_reply.".id) as totalRecord")
            );
        } else {
            $query->select([
                self::$tbl_ticket_reply. ".*",
                DB::raw(self::$tbl_ticket_reply. '.createdDate as createdDateOrigin'),
                DB::raw("FROM_UNIXTIME(".self::$tbl_ticket_reply.".createdDate, '%b - %d - %Y') as createdDate"),
                DB::raw('IF('.self::$tbl_ticket_reply.'.replyType=1,'.self::$tbl_ticket_reply.'.description,CONCAT("'.$uploadUrl .'",'.self::$tbl_ticket_reply.'.description)) as description'),
                DB::raw("tkt.userId as tkt_userId"),
            ]);
            // User::select(DB::raw("CONCAT('".$uploadUrl."', .image) as senderImage",FALSE));
            // User::select(DB::raw("CONCAT('".$thumbUrl."', .image) as thumbSenderImage",FALSE));
            $query->join(self::$tbl_ticket . ' as tkt', self::$tbl_ticket_reply. ".ticketId", "=", "tkt.id", "left");
            // $query->join(self::$tbl_users . ' as u', "tkt.userId", "=", " u.id",'left'); 
        }

        
        if (isset($data['id']) && !empty($data['id'])) {
            if (is_array($data['id'])) {
                $query->whereIn(self::$tbl_ticket_reply. '.id', $data['id']);
            } else {
                $query->Where(self::$tbl_ticket_reply. '.id', $data['id']);
            }
        }
        
        if (isset($data['ticketId'])) {
            $query->where(self::$tbl_ticket_reply. '.ticketId', $data['ticketId']);
        }
        
        if (isset($data['forReply'])) {
            $query->where(self::$tbl_ticket_reply. '.forReply', $data['forReply']);
        }
        
        if (isset($data['replyType'])) {
            $query->where(self::$tbl_ticket_reply. '.replyType', $data['replyType']);
        }
        
        if (isset($data['description'])) {
            $query->where(self::$tbl_ticket_reply. '.description', $data['description']);
        }
        
        if (isset($data['createdDate'])) {
            $query->where(self::$tbl_ticket_reply. '.createdDate', $data['createdDate']);
        }
        
        if (isset($data['status'])) {
            if (is_array($data['status'])) {
                $query->whereIn(self::$tbl_ticket_reply . '.status', $data['status']);
            } else {
                $query->where(self::$tbl_ticket_reply . '.status', $data['status']);
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
            $query->orderBy(self::$tbl_ticket_reply. '.'.$data['orderby'], (isset($data['orderstate']) && !empty($data['orderstate']) ? $data['orderstate'] : 'DESC'));
		} else {
            $query->orderBy(self::$tbl_ticket_reply. '.id', 'DESC');
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

}

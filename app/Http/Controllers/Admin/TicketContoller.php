<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Ticket as TicketModel;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class TicketContoller extends Controller
{
    protected $ticketModel;
    

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    function __construct() {
        $this->ticketModel = new TicketModel;
        
    }
    public function getTickets(){
        Session::put('page','ticket');
        $this->breadcrumb['main_bread'] = "Tickets";
        $this->breadcrumb['forward_bread'] = "Ticket";
        $this->breadcrumb['bread'] = "View Tickets";
        $this->breadcrumb['button_add'] = "Add User";
        $this->breadcrumb['button_add_link'] =  url('admin/add-edit-user');
        $this->breadcrumb['page'] =  ['headTitle' => 'Ticket'];
        $tickets = $this->ticketModel->getData(['status' => [0,1]]);
        // echo "<pre>"; print_r($tickets); die;
        return view('admin.tickets.get_ticket', [
            'breadcrumb' => $this->breadcrumb,
            'tickets' => $tickets,
        ]);
    }

    public function getTicketReply(Request $request, $id=null){
        $this->breadcrumb['main_bread'] = "Tickets";
        $this->breadcrumb['forward_bread'] = "Ticket";
        $this->breadcrumb['bread'] = "View Tickets";
        $this->breadcrumb['button_add'] = "Add User";
        $this->breadcrumb['button_add_link'] =  url('admin/add-edit-user');
        $this->breadcrumb['page'] =  ['headTitle' => 'Ticket Reply'];
        $tickets = $this->ticketModel->getTicketReply(['status' => [0,1], 'ticketId' => $id],true);
        $usrModel =  User::getData(['id' => $tickets->tkt_userId], true);
        // echo "<pre>"; print_r($usrModel); die;
        return view('admin.tickets.ticket_reply',['breadcrumb' => $this->breadcrumb, 'tickets' => $tickets, 'usrModel' =>$usrModel]);
    }

    public function deleteTicket($id = null)
    {
        if (!empty($id)) {
            $this->ticketModel->setData(['status' => 2],$id);
            return redirect()->back()->with('success_message', 'Ticket deleted Successfully!!!');
        }
    }

    public function updateTicketStatus(Request $request)
    {
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if($data['status']=="1"){
                $status = 0;
            }else{
                $status = 1;
            }
            $this->ticketModel->setData([
                'status'=> $status,
            ], $data['ticket_id']);
            return response()->json(['status'=>$status,'ticket_id'=>$data['ticket_id']]);
        }
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\AppUserFeedback as AppUserFeedbackModel;

class AppFeedbackController extends Controller
{
    protected $appUserFeedbackModel;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    function __construct() {
        $this->appUserFeedbackModel = new AppUserFeedbackModel;

    }
    public function getAppFeedback() {
        Session::put('page','appfeedback');
        $data = array(
            'main_bread' => "App Feedback",
            'forward_bread' => "App Feedback",
            'bread' => "View App Feedback",
            'bread_link' => '/admin/cms',
        );

        $appFeedback = $this->appUserFeedbackModel->getData(['status' => [0,1]]);
        // print_r($appFeedback); die;

        return view('admin.appFeedback.get_app_feedback')->with(compact('data','appFeedback'));
    }

    public function deleteAppFeedback($id = null)
    {
        if (!empty($id)) {
            $this->appUserFeedbackModel->setData(['status' => 2],$id);
            return redirect()->back()->with('success_message', 'App feedback deleted Successfully!!!');
        }
    }

    public function updateAppFeedbackStatus(Request $request)
    {
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if($data['status']=="1"){
                $status = 0;
            }else{
                $status = 1;
            }
            // User::where('id',$data['user_id'])->update(['status'=>$status]);
            $this->appUserFeedbackModel->setData([
                'status'=> $status,
            ], $data['feedback_id']);
            return response()->json(['status'=>$status,'feedback_id'=>$data['feedback_id']]);
        }
    }
}

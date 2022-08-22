<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    function __construct() {
    }
    public function dashboard() {
        Session::put('page','dashboard');
        if(session::has('adminSession')){
            // Perform All Dashboard Task
            return view('admin.admin_dashboard');
        }else{
            return redirect('/admin')->with('error','Please Login to get Access');
        }
    }

    //Login
    public function login(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            $rules = [
                'email' => 'required|email|max:255',
                'password' => 'required',
            ];

            $customMessages = [
                'email.required' =>'Email is required',
                'email.email' => 'Valid Email is required',
                'password.required' => 'Password is required'
            ];

            // $this->validate($request,$rules,$customMessages);

            if(Auth::attempt(['email'=>$data['email'],'password'=>$data['password'], 'role'=>1])){
                Session::put('adminSession', $data['email']);                
                return redirect('/admin/dashboard');
            }else{
                Session::flash('error_message','Invalid Credentials!!!');
                return redirect()->back();
            }
        }

        return view('admin.admin_login');
    }

    public function logout()
    {
        // echo "test"; die;
        Session::flush();
        return redirect('/admin')->with('success', 'Logged Out Successfully');
    }

    //Users
    public function getUsers(Request $request) {        
        Session::put('page','users');
        $this->breadcrumb['main_bread'] = "Users  ";
        $this->breadcrumb['forward_bread'] = "User";
        $this->breadcrumb['bread'] = "View Users";
        $this->breadcrumb['button_add'] = "Add User";
        $this->breadcrumb['button_add_link'] =  url('admin/add-edit-user');
        $this->breadcrumb['page'] =  ['headTitle' => 'User'];
        $users = User::getData(['status' => [0,1],'role'=>2]);
        // echo "<pre>"; print_r($this->data); die;
        return view('admin.users.get_users', [
            'breadcrumb' => $this->breadcrumb,
            'users' => $users,
        ]);
    }

    public function addUser(Request $request, $id=null) {

        $breadcrumb = array(
            'main_bread' => "Users",
            'forward_bread' => "Add User",
            'bread' => "Add User",
            'bread_link' => '/admin/add-edit-user',
            'button_add' => "Add User",
            'button_view_link' => '/admin/get-users',
            'button_add_link' => '/admin/add-edit-user',
            'page' =>  ['headTitle' => 'Add User'],
        );
        $form = array(
            'form_method' => 'post',
            'form_add_action' => url('/admin/add-edit-user'),
            'form_name' => 'userForm',
            'form_id' => 'userForm',
        );
        if($id== null) {
            $userData = array();
            $message = "User added successfully!!!!";
        }else{
            $breadcrumb['forward_bread'] = "Edit User";
            $breadcrumb['bread'] =  "Edit User";
            $breadcrumb['bread_link'] = '/admin/add-edit-user/{id?} ';
            $breadcrumb['button_add'] =  "Edit User";
            $breadcrumb['button_add_link'] =  "/admin/add-edit-user/{id?}";
            $breadcrumb['page'] =  ['headTitle' => 'Edit User'];
            //Edit User
            $userData = User::getData(['id' => $id], true);
            // echo "<pre>"; print_r($userData); die;
            $user = User::find($id);
            $message = "User updated successfully!!!!";
            $form = array(
                'form_method' => 'post',
                'form_edit_action' => url('/admin/add-edit-user/' .$userData->id),
                'form_name' => 'userForm',
                'form_id' => 'userForm',
            );
        }
        if($request->isMethod('post')){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            //User Validation
            // $rules = [
            //     'category_name' => 'required',
            //     'section_id' => 'required',
            //     'category_image' => 'image',
            //     'category_url' => 'required',
            // ];
            // $customMessages = [
            //     'category_name.required' =>'User name is required',
            //     'section_id.required' => 'Section Id is required',
            //     'category_image.image' => 'Valid image is required',
            //     'category_url.required' => 'User URL is required',
            // ];
            // $this->validate($request,$rules,$customMessages);

            //image upload
            $upload_path = env('UPLOADPATH');
            $allowed_types = array("jpg", "png", "jpeg","webp");          
            if ($request->hasFile('image')) {
                $image       = $request->image;
                // print_r($image); die;
                $fileExt = $image->getClientOriginalExtension();
                
                // die($fileExt);
                if (in_array($fileExt, $allowed_types)) {
                    $fileName = date('ymdhis') . rand(000000,999999) . '.'.$fileExt;
                    $image->move(public_path('uploads'),$fileName);
                    $data['image'] = $fileName;
                }else{
                    session::flash('error', 'Allowed only image file.');
                }
            }
            //image upload
            if (!empty($data['password']) ) $data['password'] = bcrypt($data['password']);
            if($id=="") { 
                User::setData($data);
            } else {
                User::setData($data,$id);
            }
            //  echo "<pre>"; print_r($user); die;
            
            session::flash('success_message',$message);
            return redirect('admin/get-users');
        }
        return view('admin.users.add_edit_user')->with(compact('breadcrumb','form','userData'));
    }

    public function updateUserStatus(Request $request)
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
            User::setData([
                'status'=> $status,
            ], $data['user_id']);
            return response()->json(['status'=>$status,'user_id'=>$data['user_id']]);
        }
    }

    public function deleteUser($id = null)
    {
        if (!empty($id)) {
            User::setData(['status' => 2],$id);
            return redirect()->back()->with('success_message', 'User deleted Successfully!!!');
        }
    } 
}

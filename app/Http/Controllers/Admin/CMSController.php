<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\CMS as CMSModel;
use App\Models\Faq as FaqModel;
use App\Models\ApiResponse as ApiResponseModel;
use App\Models\Blog as BlogModel;
use App\Models\Common as CommonModel;

class CMSController extends Controller
{
    protected $cmsModel;
    protected $faqModel;
    protected $apiResponseModel;
    protected $blogModel;
    protected $commonModel;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    function __construct() {
        $this->cmsModel = new CMSModel;
        $this->faqModel = new FaqModel;
        $this->apiResponseModel = new ApiResponseModel;
        $this->blogModel = new BlogModel;
        $this->commonModel = new CommonModel;

    }
    //CMS
    public function getCMS() {
        Session::put('page','cms');
        $breadcrumb = array(
            'main_bread' => "CMS  ",
            'forward_bread' => "CMS",
            'bread' => "View CMS",
            'bread_link' => '/admin/cms',
            'button_add' => "Add CMS",
            'button_add_link' => url('admin/add-edit-cms'),
            'page' => ['headTitle' => 'CMS']
        );

        $cms = $this->cmsModel->getData(['status' => [0,1]]);
        // print_r($cms); die;

        return view('admin.cms.get_cms', [
                'breadcrumb' => $breadcrumb, 'cms' => $cms]);
    }

    public function addCMS(Request $request, $id=null) {

        $breadcrumb = array(
            'main_bread' => "CMS",
            'forward_bread' => "Add CMS",
            'bread' => "Add CMS",
            'bread_link' => '/admin/add-edit-cms',
            'button_add' => "Add CMS",
            'button_view_link' => '/admin/get-cms',
            'button_add_link' => '/admin/add-edit-cms',
            'page' => ['headTitle' => 'Add CMS']
        );
        $form = array(
            'form_method' => 'post',
            'form_add_action' => url('/admin/add-edit-cms'),
            'form_name' => 'cmsForm',
            'form_id' => 'cmsForm',
        );
        $statusArray = [ '1' => 'Active', '0' =>  'Inactive'];
        if($id== null) {
            $cmsData = array();
            $message = "CMS added successfully!!!!";
        }else{
            $breadcrumb['forward_bread'] = "Edit CMS";
            $breadcrumb['bread'] =  "Edit CMS";
            $breadcrumb['bread_link'] = '/admin/add-edit-cms/{id?} ';
            $breadcrumb['button_add'] =  "Edit CMS";
            $breadcrumb['button_add_link'] =  "/admin/add-edit-cms/{id?}";
            $breadcrumb['page'] = ['headTitle' => 'Edit CMS'];
            //Edit User
            $cmsData = $this->cmsModel->getData(['id' =>$id],true);
            // echo "<pre>"; print_r($cmsData); die;
            $message = "CMS updated successfully!!!!";
            $form = array(
                'form_method' => 'post',
                'form_edit_action' => url('/admin/add-edit-cms/' .$cmsData->id),
                'form_name' => 'cmsForm',
                'form_id' => 'cmsForm',
            );
        }
        if($request->isMethod('post')){
            $data = $request->all();

            if($id=="") { 
                $this->cmsModel->setData($data);
            } else {
                $this->cmsModel->setData($data,$id);
            }
            session::flash('success_message',$message);
            return redirect('admin/get-cms');
        }
        return view('admin.cms.add_edit_cms')->with(compact('breadcrumb','form','cmsData', 'statusArray'));
    }

    public function deleteCMS($id = null)
    {
        if (!empty($id)) {
            $this->cmsModel->setData(['status' => 2],$id);
            return redirect()->back()->with('success_message', 'CMS deleted Successfully!!!');
        }
    }

    public function updateCMSStatus(Request $request)
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
            $this->cmsModel->setData([
                'status'=> $status,
            ], $data['cms_id']);
            return response()->json(['status'=>$status,'cms_id'=>$data['cms_id']]);
        }
    }

    //FAQ
    public function getFaqs() {
        Session::put('page','faq');
        $breadcrumb = array(
            'main_bread' => "FAQ  ",
            'forward_bread' => "FAQ",
            'bread' => "View FAQ",
            'bread_link' => '/admin/get-faqs',
            'button_add' => "Add FAQ",
            'button_add_link' => url('admin/add-edit-faq'),
            'page' => ['headTitle' => 'FAQ']
        );

        $faq = $this->faqModel->getData(['status' => [0,1]]);
        // print_r($faq); die;

        return view('admin.faq.get_faq', [
            'breadcrumb' => $breadcrumb, 'faq' => $faq]);
    }

    public function addFaq(Request $request, $id=null) {

        $breadcrumb = array(
            'main_bread' => "FAQ",
            'forward_bread' => "Add FAQ",
            'bread' => "Add FAQ",
            'bread_link' => '/admin/add-edit-faq',
            'button_add' => "Add FAQ",
            'button_view_link' => '/admin/get-faqs',
            'button_add_link' => '/admin/add-edit-faq',
            'page' => ['headTitle' => 'Add FAQ']
        );
        $form = array(
            'form_method' => 'post',
            'form_add_action' => url('/admin/add-edit-faq'),
            'form_name' => 'faqForm',
            'form_id' => 'faqForm',
        );
        $statusArray = [ '1' => 'Active', '0' =>  'Inactive'];
        if($id== null) {
            $faqData = array();
            $message = "FAQ added successfully!!!!";
        }else{
            $breadcrumb['forward_bread'] = "Edit FAQ";
            $breadcrumb['bread'] =  "Edit FAQ";
            $breadcrumb['bread_link'] = '/admin/add-edit-faq/{id?} ';
            $breadcrumb['button_add'] =  "Edit FAQ";
            $breadcrumb['button_add_link'] =  "/admin/add-edit-faq/{id?}";
            $breadcrumb['page'] = ['headTitle' => 'Edit FAQ'];
            //Edit User
            $faqData = $this->faqModel->getData(['id' =>$id],true);
            // echo "<pre>"; print_r($faqData); die;
            $message = "FAQ updated successfully!!!!";
            $form = array(
                'form_method' => 'post',
                'form_edit_action' => url('/admin/add-edit-faq/' .$faqData->id),
                'form_name' => 'faqForm',
                'form_id' => 'faqForm',
            );
        }
        if($request->isMethod('post')){
            $data = $request->all();

            if($id=="") { 
                $this->faqModel->setData($data);
            } else {
                $this->faqModel->setData($data,$id);
            }
            session::flash('success_message',$message);
            return redirect('admin/get-faqs');
        }
        return view('admin.faq.add_edit_faq')->with(compact('breadcrumb','form','faqData','statusArray'));
    }

    public function updateFaqStatus(Request $request)
    {

        if($request->ajax()){
            $data = $request->all();
            if($data['status']=="1"){
                $status = 0;
            }else{
                $status = 1;
            }
            $this->faqModel->setData([
                'status'=> $status,
            ], $data['faq_id']);
            return response()->json(['status'=>$status,'faq_id'=>$data['faq_id']]);
        }
    }

    public function deleteFaq($id = null)
    {
        if (!empty($id)) {
            $this->faqModel->setData(['status' => 2],$id);
            return redirect()->back()->with('success_message', 'FAQ deleted Successfully!!!');
        }
    }

    //Api Response
    public function getApiResponse() {
        Session::put('page','apiresponse');
        $breadcrumb = array(
            'main_bread' => "Api Response",
            'forward_bread' => "Api Response",
            'bread' => "View Api Response",
            'bread_link' => '/admin/get-api-responses',
            'button_add' => "Add Api Response",
            'button_add_link' => url('admin/add-edit-api-response'),
            'page' => ['headTitle' => 'Api Response']
        );

        $apiResponses = $this->apiResponseModel->getData(['status' => [0,1]]);;
        // echo "<pre>";print_r($apiResponses); die;

        return view('admin.apiResponse.get_api_response', [
            'breadcrumb' => $breadcrumb, 'apiResponses' => $apiResponses]);
    }

    public function editApiResponse(Request $request, $id=null) {

        $breadcrumb = array(
            'main_bread' => "Api Response",
            'forward_bread' => "Edit Api Response",
            'bread' => "Edit Api Response",
            'bread_link' => '/admin/add-edit-api-response',
            'button_add' => "Edit Api Response",
            'button_add_link' => "/admin/add-edit-api-response",
            'page' => ['headTitle' => 'Add Response']
        );
        $apiResponseData = $this->apiResponseModel->getData(['id' => $id]);
        // echo "<pre>"; print_r($apiResponseData); die;
        $form = array(
            'form_method' => 'post',
            'form_add_action' => url('/admin/add-edit-api-response'),
            'form_name' => 'apiResponseForm',
            'form_id' => 'apiResponseForm',
        );

        if($id == null) {
            $apiResponseData = array();
            $message = "Api Response added successfully!!!!";
        } else {
            $breadcrumb = array(
                'main_bread' => "Api Response",
                'forward_bread' => "Edit Api Response",
                'bread' => "Edit Api Response",
                'bread_link' => '/admin/add-edit-api-response/{id}',
                'button_add' => "Edit Api Response",
                'button_add_link' => "/admin/add-edit-api-response/{id}",
                'page' => ['headTitle' => 'Edit Response']
            );
            $apiResponseData = $this->apiResponseModel->getData(['id' => $id]);
            // echo "<pre>"; print_r($apiResponseData); die;
            $form = array(
                'form_method' => 'post',
                'form_edit_action' => url('/admin/add-edit-api-response/' .$apiResponseData->id),
                'form_name' => 'apiResponseForm',
                'form_id' => 'apiResponseForm',
            );
        }
        $statusArray = [ '1' => 'Active', '0' =>  'Inactive'];
        if($request->isMethod('post')){
            $data = $request->all();

            //Edit User
            $message = "Api response updated successfully!!!!";
            $this->apiResponseModel->setData($data,$id);
            session::flash('success_message',$message);
            return redirect('admin/get-api-responses');
        }
        return view('admin.apiResponse.edit_api_response')->with(compact('breadcrumb','form','apiResponseData','statusArray'));
    }

    public function updateApiResponseStatus(Request $request, $id=null)
    {
        if($request->ajax()){
            $data = $request->all();
            if($data['status']=="1"){
                $status = 0;
            }else{
                $status = 1;
            }
            $this->apiResponseModel->setData([
                'status'=> $status,
            ], $data['apiResponse_id']);
            return response()->json(['status'=>$status,'apiResponse_id'=>$data['apiResponse_id']]);
        }
    }

    public function deleteApiResponse($id = null)
    {
        if (!empty($id)) {
            $this->apiResponseModel->setData(['status' => 2],$id);
            return redirect()->back()->with('success_message', 'Api response deleted Successfully!!!');
        }
    }

    //Blog
    public function getBlogs() {
        Session::put('page','blog');
        $breadcrumb = array(
            'main_bread' => "Blog",
            'forward_bread' => "Blog",
            'bread' => "View Blog",
            'bread_link' => '/admin/get-blogs',
            'button_add' => "Add Blog",
            'button_add_link' => url('admin/add-edit-blog'),
            'page' => ['headTitle' => 'Blog']
        );
        $blogs = $this->blogModel->getData(['status' => [0,1]]);;
        // echo "<pre>";print_r($apiResponses); die;

        return view('admin.blogs.get_blogs', [
            'breadcrumb' => $breadcrumb, 'blogs' => $blogs]);
    }

    public function addEditBlog(Request $request, $id=null) {

        $breadcrumb = array(
            'main_bread' => "Blog",
            'forward_bread' => "Add Blog",
            'bread' => "Add Blog",
            'bread_link' => '/admin/add-edit-blog',
            'button_add' => "Add Blog",
            'button_view_link' => '/admin/get-blogs',
            'button_add_link' => '/admin/add-edit-blog',
            'page' => ['headTitle' => 'Add Blog']
        );
        $form = array(
            'form_method' => 'post',
            'form_add_action' => url('/admin/add-edit-blog'),
            'form_name' => 'blogForm',
            'form_id' => 'blogForm',
        );
        $statusArray = [ '1' => 'Active', '0' =>  'Inactive'];
        if($id== null) {
            $blogData = array();
            $message = "Blog added successfully!!!!";
        }else{
            $breadcrumb['forward_bread'] = "Edit Blog";
            $breadcrumb['bread'] =  "Edit Blog";
            $breadcrumb['bread_link'] = '/admin/add-edit-blog/{id?} ';
            $breadcrumb['button_add'] =  "Edit Blog";
            $breadcrumb['button_add_link'] =  "/admin/add-edit-blog/{id?}";
            $breadcrumb['page'] = ['headTitle' => 'Edit Blog'];
            //Edit User
            $blogData = $this->blogModel->getData(['id' =>$id],true);
            // echo "<pre>"; print_r($blogData); die;
            $message = "Blog updated successfully!!!!";
            $form = array(
                'form_method' => 'post',
                'form_edit_action' => url('/admin/add-edit-blog/' .$blogData->id),
                'form_name' => 'blogForm',
                'form_id' => 'blogForm',
            );
        }
        if($request->isMethod('post')){
            $data = $request->all();
            if (isset($data['createdDate']) && !empty($data['createdDate'])) {
                $data['createdDate'] = strtotime($data['createdDate']);
            }
            // echo "<pre>"; print_r($data); die();
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
                    // $upload_dir = $upload_path . "/" . $fileName;
                    // if ($image->move(public_path($upload_dir))) {
                    //     $data['image'] = $fileName;
                    // }
                }else{
                    session::flash('error', 'Allowed only image file.');
                    // return redirect('admin/resources/' . $data['id']);
                }
            }
            //image upload
            if($id=="") { 
                $this->blogModel->setData($data);
            } else {
                $this->blogModel->setData($data,$id);
            }
            session::flash('success_message',$message);
            return redirect('admin/get-blogs');
        }
        return view('admin.blogs.add_edit_blog')->with(compact('breadcrumb','form','blogData','statusArray'));
    }

    public function updateBlogStatus(Request $request, $id=null)
    {
        if($request->ajax()){
            $data = $request->all();
            if($data['status']=="1"){
                $status = 0;
            }else{
                $status = 1;
            }
            $this->blogModel->setData([
                'status'=> $status,
            ], $data['blog_id']);
            return response()->json(['status'=>$status,'blog_id'=>$data['blog_id']]);
        }
    }

    public function deleteBlog($id = null)
    {
        if (!empty($id)) {
            $this->blogModel->setData(['status' => 2],$id);
            return redirect()->back()->with('success_message', 'Blog deleted Successfully!!!');
        }
    }
}

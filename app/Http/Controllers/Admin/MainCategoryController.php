<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\MainCategory as MainCategoryModel;

class MainCategoryController extends Controller
{
    protected $mainCategoryModel;

    /**
     * Instantiate a new controller instance.
     *
     * @return void
     */
    function __construct() {
        $this->mainCategoryModel = new MainCategoryModel;

    }
    
    public function getMainCategory() {
        Session::put('page','mainCategory');
        $breadcrumb = array(
            'main_bread' => "Main Category",
            'forward_bread' => "Main Category",
            'bread' => "View Main Category",
            'bread_link' => '/admin/get-main-category',
            'button_add' => "Add Main Category",
            'button_add_link' => url('admin/add-edit-main-category'),
            'page' => ['headTitle' => 'Category']
        );

        $mainCategory = $this->mainCategoryModel->getData(['status' => [0,1]]);
        // print_r($mainCategory); die;

        return view('admin.mainCategory.get_main_categories', [
                'breadcrumb' => $breadcrumb, 'mainCategory' => $mainCategory]);
    }

    public function addEditMainCategory(Request $request, $id=null) {

        $breadcrumb = array(
            'main_bread' => "Main Category",
            'forward_bread' => "Add Main Category",
            'bread' => "Add Main Category",
            'bread_link' => '/admin/add-edit-main-category',
            'button_add' => "Add Main Category",
            'button_view_link' => '/admin/get-main-category',
            'button_add_link' => '/admin/add-edit-main-category',
            'page' => ['headTitle' => 'Add Category']
        );
        $form = array(
            'form_method' => 'post',
            'form_add_action' => url('/admin/add-edit-main-category'),
            'form_name' => 'mainCategoryForm',
            'form_id' => 'mainCategoryForm',
        );
        $statusArray = [ '1' => 'Active', '0' =>  'Inactive'];
        if($id== null) {
            $mainCategoryData = array();
            $message = "Main Category added successfully!!!!";
        }else{
            $breadcrumb['forward_bread'] = "Edit Main Category";
            $breadcrumb['bread'] =  "Edit Main Category";
            $breadcrumb['bread_link'] = '/admin/add-edit-main-category/{id?} ';
            $breadcrumb['button_add'] =  "Edit Main Category";
            $breadcrumb['button_add_link'] =  "/admin/add-edit-main-category/{id?}";
            $breadcrumb['page'] = ['headTitle' => 'Edit Category'];
            //Edit User
            $mainCategoryData = $this->mainCategoryModel->getData(['id' =>$id],true);
            // echo "<pre>"; print_r($mainCategoryData); die;
            $message = "Main Category updated successfully!!!!";
            $form = array(
                'form_method' => 'post',
                'form_edit_action' => url('/admin/add-edit-main-category/' .$mainCategoryData->id),
                'form_name' => 'mainCategoryForm',
                'form_id' => 'mainCategoryForm',
            );
        }
        if($request->isMethod('post')){
            $data = $request->all();
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
                    // print_r($data['image']); die;
                }else{
                    session::flash('error', 'Allowed only image file.');
                }
            }
            //image upload
            if($id=="") { 
                $this->mainCategoryModel->setData($data);
            } else {
                $this->mainCategoryModel->setData($data,$id);
            }
            session::flash('success_message',$message);
            return redirect('admin/get-main-category');
        }
        return view('admin.mainCategory.add_edit_main_category')->with(compact('breadcrumb','form','mainCategoryData', 'statusArray'));
    }

    public function deleteMainCategory($id = null)
    {
        if (!empty($id)) {
            $this->mainCategoryModel->setData(['status' => 2],$id);
            return redirect()->back()->with('success_message', 'Main Category deleted Successfully!!!');
        }
    }

    public function updateMainCategoryStatus(Request $request)
    {
        if($request->ajax()){
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if($data['status']=="1"){
                $status = 0;
            }else{
                $status = 1;
            }
            $this->mainCategoryModel->setData([
                'status'=> $status,
            ], $data['mainCategory_id']);
            return response()->json(['status'=>$status,'mainCategory_id'=>$data['mainCategory_id']]);
        }
    }
}

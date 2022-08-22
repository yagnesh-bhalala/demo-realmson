<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\CMS as CMSModel;

class FrontController extends Controller
{
    protected $cmsModel;

    function __construct() {
        $this->cmsModel = new CMSModel;
    }
    public function dashboard() {
        return view ('front.front_dashboard');
    }

    public function aboutUs() {
        return view ('front.about_us');
    }

    public function faq() {
        return view ('front.under_construction');
    }

    public function pricing() {
        return view ('front.under_construction');
    }

    public function blogs() {
        return view ('front.under_construction');
    }

    public function blogDetails() {
        return view ('front.blog_details');
    }
}

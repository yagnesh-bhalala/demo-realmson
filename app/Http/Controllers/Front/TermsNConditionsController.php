<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\CMS as CMSModel;

class TermsNConditionsController extends Controller
{
    protected $cmsModel;

    function __construct() {
        $this->cmsModel = new CMSModel;
    }
    public function termsAndConditions(Request $request) {
        // Session::forget('page');
        $cms = $this->cmsModel->getData(['status' => [0,1], 'key' => 'termscondition'], true);      
        return view ('front.terms_conditions',['cms' => $cms]);
    }
}

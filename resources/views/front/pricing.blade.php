@extends('front.frontLayout.front_design')

@section('title', 'Pricings')

@section('content')

<!-- header -->
<div class="main_header_Section about_us_main">
    <div class="container">
        <header class="navigation_sec_head">
            <div class="col-xl-12 d-flex justify-content-end pt-3">
                <div class="side_drop_down_top black_drop">
                    <div class="dropdown show">
                        <a class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            English <img src="{{ url('public/frontend/image/icon_dropdown.png') }}" alt="image" class="img-fluid">
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="#">Action</a>
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="#">Something else here</a>
                        </div>
                    </div>
                </div>
            </div>
            @include('front.frontLayout.front_header')
        </header>
    </div>
    <div class="text_on_img col-xl-12 col-lg-12 col-md-12 d-flex justify-content-end align-items-center flex-wrap pt-5 px-0">
        <div class="col-xl-5 col-lg-6 col-md-6 ">            
            <div class="img_bg_about">
                <img src="{{ url('public/frontend/image/pricing_text.png') }}" class="img-fluid">
                <div class="absolute_text">
                    <h4>Pricing</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 pr-0">
           <div class="img_right_side">
               <img src="{{ url('public/frontend/image/pricing_main_img.png') }}" class="img-fluid">
           </div>
        </div>
    </div>
</div>
<!-- /header -->


<div class="bg_round">
  
    <section>
        <div class="container">
            <div class="col-xl-12 ">
                <div class="col-xl-6 col-lg-8 col-md-12 col-12 mx-auto">
                    <div class="treck_Sec text-center">                        
                        <h4>Lorem ipsum is simple dummy text of the printing and typesetting</h4>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-8 col-md-10 col-12 mx-auto text-center my-4 small_treck">
                    <p>
                        Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                    </p>                                 
                </div>
            </div>
        </div>   

        <div class="container pt-5">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12 d-flex align-items-center justify-content-center flex-wrap">
                <div class="col-xl-4 col-lg-6 col-md-6 col-12 pt-lg-0 pt-3 px-md-4 px-0">
                    <div class="white_box">
                        <img src="{{ url('public/frontend/image/pricing_1.png') }}" class="img-fluid">
                        <h4>Basic</h4>
                        <div class="price_sec">
                            <h2>$10.00/<span>Monthly</span></h2>
                        </div>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when.</p>
                        <div class="list_price">
                            <div class="d-flex align-items-center "> 
                                <i class="fas fa-check"></i>
                                <p>Lorem Ipsum is simply dummy text of the</p>
                            </div>
                            <div class="d-flex align-items-center "> 
                                <i class="fas fa-check"></i>
                                <p>Lorem Ipsum is simply dummy text of the</p>
                            </div>
                            <div class="d-flex align-items-center "> 
                                <i class="fas fa-check"></i>
                                <p>Lorem Ipsum is simply dummy text of the</p>
                            </div>
                            <div class="d-flex align-items-center"> 
                                <i class="fas fa-check"></i>
                                <p>Lorem Ipsum is simply dummy text of the</p>
                            </div>
                            <div class="d-flex align-items-center"> 
                                <i class="fas fa-check"></i>
                                <p>Lorem Ipsum is simply dummy text of the</p>
                            </div>
                         
                        </div>
                        <div class="button_bottom">
                            <button class="join_btn">
                                Join Basic
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6 col-md-6 col-12 pt-lg-0 pt-3 px-md-4 px-0">
                    <div class="white_box active">
                        <img src="{{ url('public/frontend/image/pricing_1.png') }}" class="img-fluid">
                        <h4>Basic</h4>
                        <div class="price_sec">
                            <h2>$10.00/<span>Monthly</span></h2>
                        </div>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when.</p>
                        <div class="list_price">
                            <div class="d-flex align-items-center "> 
                                <i class="fas fa-check"></i>
                                <p>Lorem Ipsum is simply dummy text of the</p>
                            </div>
                            <div class="d-flex align-items-center "> 
                                <i class="fas fa-check"></i>
                                <p>Lorem Ipsum is simply dummy text of the</p>
                            </div>
                            <div class="d-flex align-items-center "> 
                                <i class="fas fa-check"></i>
                                <p>Lorem Ipsum is simply dummy text of the</p>
                            </div>
                            <div class="d-flex align-items-center"> 
                                <i class="fas fa-check"></i>
                                <p>Lorem Ipsum is simply dummy text of the</p>
                            </div>
                            <div class="d-flex align-items-center"> 
                                <i class="fas fa-check"></i>
                                <p>Lorem Ipsum is simply dummy text of the</p>
                            </div>
                         
                        </div>
                        <div class="button_bottom">
                            <button class="join_btn">
                                Join Gold
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
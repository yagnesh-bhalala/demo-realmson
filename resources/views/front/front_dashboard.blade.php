@extends('front.frontLayout.front_design')

@section('title', 'Home')

@section('content')

<!-- header -->
<div class="main_header_Section main_title_img">
    <div class="container">
        <header class="navigation_sec_head">
            <div class="col-xl-12 d-flex justify-content-end pt-3">
                <div class="side_drop_down_top">
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
    <div class="text_on_img col-xl-12 col-lg-12 col-md-12 d-flex justify-content-center flex-wrap pt-5">
        <div class="col-xl-5 col-lg-7 col-md-11 ">
            <div class="text_head">
                <p class="py-4">Experience the <b>easiest</b> way to balance your <b>bills and spending</b> with The <b>5 Dollar Bill Helper</b> <sup>TM</sup> </p>
                <small><b>Download the app and never miss a bill payment. Available in multiple languages for Android and IOS devices.</b></small>
                <div class="button_head pt-4">
                    <a href="#" class="mr-5"><img src="{{ url('public/frontend/image/app_Store_white.png') }}"></a>
                    <a href="#"><img src="{{ url('public/frontend/image/play_store_white.png') }}"></a>
                </div>
            </div>
            <div class="img_animate_arrow mt-5">
                <img src="{{ url('public/frontend/image/arrow_animate.png') }}" class="img-fluid">
            </div>
        </div>
        <div class="col-xl-5 col-lg-5 col-md-8 on_right_side">
            <div class="img_on_header">
                <img src="{{ url('public/frontend/image/main_head_image.png') }}" class="img-fluid">
            </div>
        </div>
    </div>
</div>
<!-- /header -->

<div class="bg_round">
    <!-- section -->
    <div class="container">
        <div class="col-xl-12 ">
            <div class="col-xl-8 col-lg-9 col-md-12 col-12 mx-auto">
                <div class="treck_Sec text-center">
                    <p>Track all your finances with the unique yet simple</p>
                    <h4>5 Dollar Bill Helper <SUP>TM</SUP> application</h4>
                </div>
            </div>
            <div class="col-xl-8 col-lg-6 col-md-10 col-12 mx-auto text-center my-4 small_treck">
                <p>The app is easy to use and was designed for people of all ages.
                    You can choose to use the app manually.
                    But to experience the full potential of The 5 Dollar Bill Helper link your financial institutions.!
                    With The <b> 5 Dollar Bill Helper </b> <sup>TM</sup> you get:
                </p>
                <div class="col-xl-12 col-lg-12 col-md-12 col-12 d-flex align-items-center justify-content-center flex-wrap">
                    <div class="col-xl-4 col-lg-6 col-md-8 col-12 ">
                        <div class="check_Sec d-flex align-items-center my-4">
                            <img src="{{ url('public/frontend/image/right_icon.png') }}" alt="image">
                            <h4 class="ml-2">The Bill Balancer</h4>
                        </div>
                        <div class="check_Sec d-flex align-items-center my-4">
                            <img src="{{ url('public/frontend/image/right_icon.png') }}" alt="image">
                            <h4 class="ml-2">Details of all your payments</h4>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6 col-md-8 col-12 ">
                        <div class="check_Sec d-flex align-items-center my-4">
                            <img src="{{ url('public/frontend/image/right_icon.png') }}" alt="image">
                            <h4 class="ml-2">Payment tracker</h4>
                        </div>
                        <div class="check_Sec d-flex align-items-center my-4">
                            <img src="{{ url('public/frontend/image/right_icon.png') }}" alt="image">
                            <h4 class="ml-2">Track all pending and cleared payments</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section>
        <div class="container">
            <div class="col-xl-12 ">
                <div class="col-xl-6 mx-auto">
                    <div class="treck_Sec text-center">
                        <h4>Join the best financial management and balancing system today!</h4>
                    </div>
                </div>
                <div class="col-xl-5 mx-auto text-center my-4 small_treck">
                    <p>Create your account and start earning reward coins. Reward coins can be used for one free monthly subscription. </p>
                    <div class="button_Sec_account my-5">
                        <button class="btn btn-create">
                            <p>Create Account <i class="fas fa-long-arrow-alt-right"></i></p>
                            <img src="{{ url('public/frontend/image/border-bottom.png') }}" class="border_diaplay d-none">
                        </button>
                        <button class="btn btn-create active">
                            <p>Pricing <i class="fas fa-long-arrow-alt-right"></i></p>
                            <img src="{{ url('public/frontend/image/border-bottom.png') }}" class="border_diaplay d-none">
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /section -->
</div>

<!-- section -->
<div class="bg_grey">
    <div class="container py-3">
        <div class="row">
            <div class="col-xl-9 col-lg-11 col-md-11 col-sm-12 d-flex align-items-center justify-content-center mx-auto bg_sec_coloured flex-wrap">
                <div class="col-xl-5 col-lg-5 col-md-5">
                    <div class="phn_section">
                        <img src="{{ url('public/frontend/image/mobile_photo.png') }}" class="img-fluid">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-7">
                    <div class="text_sectin_side">
                        <h4>Track all your expenses with <span>just a click</span></h4>
                        <p>The app tracks all your payments and offers you a monthly summary of expenditure. You can easily track the amount you have spent and where you have spent it.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container py-3">
        <div class="row">
            <div class="col-xl-9 col-lg-11 col-md-11 col-sm-12 d-flex align-items-center justify-content-center mx-auto cream_bg flex-wrap">
                <div class="col-xl-6 col-lg-6 col-md-7 order-md-1 order-2">
                    <div class="text_sectin_side left_side">
                        <h4>Due Date <span>Reminder </span></h4>
                        <p>The application reminds you of all the due dates so you never miss a payment. With the help of The 5 Dollar Bill Helper you can make every payment on time.</p>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-5 col-md-5 order-md-2 order-1">
                    <div class="phn_section_left">
                        <img src="{{ url('public/frontend/image/duedaterem.png') }}" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container py-3">
        <div class="row">
            <div class="col-xl-9 col-lg-11 col-md-11 col-sm-12 d-flex align-items-center justify-content-center mx-auto bg_sec_coloured sky_bg flex-wrap">
                <div class="col-xl-5 col-lg-5 col-md-5">
                    <div class="phn_section">
                        <img src="{{ url('public/frontend/image/userinterface.png') }}" class="img-fluid">
                    </div>
                </div>
                <div class="col-xl-6 col-lg-6 col-md-7">
                    <div class="text_sectin_side">
                        <h4>User-Friendly <span>Interface</span></h4>
                        <p>Managing your finances is tough, yet we make it easy for you. With just a few clicks you can check everything related to your bills and payments.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container py-3">
    <div class="row">
        <div class="col-xl-9 col-lg-11 col-md-11 col-sm-12 d-flex align-items-center justify-content-center mx-auto green_bg flex-wrap">
            <div class="col-xl-6 col-lg-5 col-md-7 order-md-1 order-2">
                <div class="text_sectin_side left_side">
                    <h4>Balance your <span>bills </span>and <span>spending!</span></h4>
                    <p>Use The Bill Balancer to foresee upcoming payments and guarantee you will have enough money to pay the bills! The unique Bill Balancer splits payments by making sure your bills are balanced from pay check to pay check. It also looks at your spending money to balance how much you have to left to spend each pay period!</p>
                </div>
            </div>
            <div class="col-xl-5 col-lg-6 col-md-5 order-md-2 order-1">
                <div class="phn_section_left">
                    <img src="{{ url('public/frontend/image/billspending.png') }}" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</div>
<!--/ section -->

<!-- section -->
<div class="bg_grey_small">
    <div class="container">
        <section>
            <div class="row">
                <div class="col-xl-12">
                    <div class="col-xl-8 col-lg-10 col-md-10 col-12 mx-auto text-center how_work_title">
                        <h4>How It Works</h4>
                        <p>
                            This app is for everyone from high school student to retiree. IT'S THAT EASY! We do not recommend creating envelopes or getting a loan,
                            and we do not recommend you get paid earlier than expected! And you shouldn't have to cancel the subscriptions you really want! Instead,
                            our app helps you balance your bills and your spending money. YOU HAVE CONTROL over YOUR MONEY!
                            The 5 Dollar Bill Helper TM uses The Bill Balancer TM (Patent Pending) to split your bills prior to the due date.
                            This ensures you have enough money to pay your bills and know your spending limits paycheck to paycheck.
                            And the amounts for both expenses and spending will average the same amount each pay period.
                            Oh, and you can use our app manually or link your bank account, whichever you feel more comfortable doing. Either way this app is your spreadsheet taken to the next level!
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="row pt-5">
                <div class="col-xl-8 col-lg-12 col-md-12 d-flex align-items-center justify-content-center mx-auto flex-wrap py-3">
                    <div class="col-xl-6 col-lg-6 col-md-6 ">
                        <div class="step_follow">
                            <p>Follow the simple 5-step setup process and get rid of all your <span>financial worries.</span></p>
                            <img src="{{ url('public/frontend/image/line_border.png') }}" class="img-fluid py-4">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 ">
                        <div class="register_self">
                            <h4><span>1</span> Register Yourself</h4>
                        </div>
                        <div class="detail_register">
                            <p>Download the application and register some of your personal and financial information with just a few clicks.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8  col-md-12 mx-auto py-3">
                    <div class="col-xl-6 col-lg-6 col-md-8">
                        <div class="register_self orange_txt">
                            <h4><span>2</span> Add your expenses</h4>
                        </div>
                        <div class="detail_register">
                            <p>Now add your expenses according to their due dates. All your added expenses will be shown on the expense tab on the dashboard.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8 col-md-12  mx-auto py-3">
                    <div class="col-xl-6 col-lg-6 col-md-8 ml-auto">
                        <div class="register_self sky_txt">
                            <h4><span>3</span> Clear Dues</h4>
                        </div>
                        <div class="detail_register">
                            <p>Never forget to pay Bills with the due date reminder. Track your pending vs. cleared expenses manually or link your Bank account for faster automation.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8  col-md-12 mx-auto py-3">
                    <div class="col-xl-6 col-lg-6 col-md-8 ">
                        <div class="register_self green_txt">
                            <h4><span>4</span> Check Monthly expenditure</h4>
                        </div>
                        <div class="detail_register">
                            <p>See where your money is going. The application offers an expense tracker to help you understand your spending habits.</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-8  col-md-12 mx-auto py-3">
                    <div class="col-xl-6 col-lg-6 col-md-8 ml-auto">
                        <div class="register_self d-flex">
                            <h4><span>5</span> Use the Bill Balancer <sup>TM</sup></h4>
                        </div>
                        <div class="detail_register">
                            <p>
                                Balance your expenses and spending money in you current and future pay periods. The Bill Balancer will help to ensure you will always have an equal amount of bills and spending money each paycheck.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<!-- /section -->

<!-- section -->
<section class="print_Sec">
    <div class="container">
        <div class="col-xl-10 col-lg-12 col-md-12 col-sm-12 d-flex align-items-center justify-content-center mx-auto flex-wrap">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div class="printing_text">
                    <h4>Balance Your Bills,<br> Balance Your Life! <br>
                    <span>with the 5Dollar Bill Helper<sup> TM </sup></span>
                    </h4>
                    <p>Balanced Bills to ensure you always have enough to pay your debts, Balanced spending money so you always know you have a consistent amount of money at your disposal every pay period.</p>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mt-md-0 mt-5">
                <div class="img_on_slider">
                    <img src="{{ url('public/frontend/image/side_img.png') }}" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- /section -->

<!-- slider section -->
    <Section class="slider_bg">
        <div class="col-xl-12 midddal_sec_main owl-carousel owl-theme">
            <div class="slider_main_section item ">
                <div class="white_box_slider">
                    <div class="icon_title">
                        <img src="{{ url('public/frontend/image/feedback1.png') }}" class="img-fluid">
                    </div>                  
                    <div class="name_about d-flex justify-content-between py-3">
                        <h4>Jennifer William</h4>
                        <div class="star_section d-flex align-items-center">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="discription_about">
                        <p>Grade 9</p>
                        <p class="pt-4">Works like a charm. I love the ease of the application which helps me to track my bills easily and I would highly recommend purchasing their yearly subscription. You will use this app daily!</p>
                    </div>
                    
                </div>
            </div>
            <div class="slider_main_section item ">
                <div class="white_box_slider">
                    <div class="icon_title">
                        <img src="{{ url('public/frontend/image/feedback2.png') }}" class="img-fluid">
                    </div>                  
                    <div class="name_about d-flex justify-content-between py-3">
                        <h4>Robert Smith</h4>
                        <div class="star_section d-flex align-items-center">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="discription_about">
                        <p>Grade 9</p>
                        <p class="pt-4">I am so excited I downloaded the $5 Bill Helper app! Each feature of this app helps me to create a balance of my bills and always have money in my pocket!  The app lets me see into the future so I can make sure I have enough money to pay future bills. This kind of detail would be impossible to know without the $5 Bill Helper. I highly recommend it!</p>
                    </div>
                    
                </div>
            </div>
            <div class="slider_main_section item ">
                <div class="white_box_slider">
                    <div class="icon_title">
                        <img src="{{ url('public/frontend/image/feedback3.png') }}" class="img-fluid">
                    </div>                  
                    <div class="name_about d-flex justify-content-between py-3">
                        <h4>James Johnson</h4>
                        <div class="star_section d-flex align-items-center">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="discription_about">
                        <p>Grade 12</p>
                        <p class="pt-4">This app has allowed me to have the same amount of spending money each pay check. I stopped spending money I didn’t really have. I even started saving! Thank you $5 Bill Helper! And the Bill Balancer is really cool too!</p>
                    </div>
                    
                </div>
            </div>
            <div class="slider_main_section item ">
                <div class="white_box_slider">
                    <div class="icon_title">
                        <img src="{{ url('public/frontend/image/feedback4.jpg') }}" class="img-fluid">
                    </div>                  
                    <div class="name_about d-flex justify-content-between py-3">
                        <h4>Steve Smith</h4>
                        <div class="star_section d-flex align-items-center">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="discription_about">
                        <p>Grade 10</p>
                        <p class="pt-4">I have used many finance management applications, but have never found any application as efficient as 5 Dollar Bill Helper. Totally loved it.</p>
                    </div>
                    
                </div>
            </div>
            <div class="slider_main_section item ">
                <div class="white_box_slider">
                    <div class="icon_title">
                        <img src="{{ url('public/frontend/image/feedback5.jpg') }}" class="img-fluid">
                    </div>                  
                    <div class="name_about d-flex justify-content-between py-3">
                        <h4>Maria Garcia</h4>
                        <div class="star_section d-flex align-items-center">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <div class="discription_about">
                        <p>Grade 11</p>
                        <p class="pt-4">I have used many finance management applications, but have never found any application as efficient as 5 Dollar Bill Helper. Totally loved it.</p>
                    </div>
                    
                </div>
            </div>
        </div>
    </Section> 
<!-- /slider section -->
@endsection
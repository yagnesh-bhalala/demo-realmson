@extends('front.frontLayout.front_design')

@section('title', 'FAQs')

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
            <div class="img_bg_about text-center">
                <img src="{{ url('public/frontend/image/faq_text.png') }}" class="img-fluid">
                <div class="absolute_text">
                    <h4>FAQs</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 pr-0">
            <div class="img_right_side">
                <img src="{{ url('public/frontend/image/faq_main_img.png') }}" class="img-fluid">
            </div>
        </div>
    </div>
</div>
<!-- /header -->


<div class="bg_round">

    <section>
        <div class="container">
            <div class="col-xl-12 ">
                <div class="col-xl-5 col-lg-8 col-md-12 col-12 mx-auto">
                    <div class="treck_Sec text-center">
                        <p>Lorem Ipsum is simply </p>
                        <h4>dummy text of the printing.</h4>
                    </div>
                </div>
                <div class="col-xl-5 col-lg-8 col-md-10 col-12 mx-auto text-center my-4 small_treck">
                    <p>
                    Got bunch of questions which is making you wonder how just an application can help you manage your funds with ease?
                        Then this is the right place to clear your doubts.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-xl-12 px-md-auto px-0">
                    <div class="col-xl-8 col-lg-8 col-md-10 col-12 mx-auto px-md-auto px-0">
                        <div class="faq_Sec">
                            <div class="blue_box">
                                <div class="accordion mx-auto" id="accordionExample">
                                    <div class="item">
                                        <div class="item-header" id="headingOne">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                How can this Application help in a way of tracking our financial structure?
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                            <div class="t-p">
                                                <p>The app tracks all your payments and offers you a monthly summary of expenditure. Just by adding in your bank details you can easily track the amount you have spent and where you have spent it. 5 Dollar Application works like your personal financial diary which helps you get a detail overview by tracking all your financial movement.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item-header" id="headingTwo">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                How does the Application works?
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                            <div class="t-p">
                                                <p>5 Dollar helps you balance your bills and your spending money. Having a control over your money is a good deal especially in this generation.</p><br>
                                                <p>The 5 Dollar Bill Helper TM uses The Bill Balancer <sup>TM</sup> (Patent Pending) to split your bills prior to the due date. This ensures you have enough money to pay your bills and know your spending limits pay check to pay check.</p><br>
                                                <p>And the amounts for both expenses and spending will average the same amount each pay period. The Application manually links your bank account, whichever you feel more comfortable doing. Either way this app is your spreadsheet taken to the next level!</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item-header" id="headingThree">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                The 5-step process to describe the operation of the Application 
                                                    <i class="fa fa-angle-down"></i>
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                            <div class="t-p">
                                                <li>Register Yourself</li><br>
                                                <ul>Download the application and register some of your personal and financial information with just a few clicks.</ul>
                                                <li>Add your expenses</li><br>
                                                <ul>Now add your expenses according to their due dates. All your added expenses will be shown on the expense tab on the dashboard.</ul>
                                                <li>Clear Dues</li><br>
                                                <ul>Never forget to pay Bills with the due date reminder. Track your pending vs. cleared expenses manually or link your Bank account for faster automation.</ul>
                                                <li>Check Monthly Expenditure </li><br>
                                                <ul>See where your money is going. The application offers an expense tracker to help you understand your spending habits.</ul>
                                                <li>Use the Bill Balancer</li><br>
                                                <ul>Balance your expenses and spending money in your current and future pay periods. The Bill Balancer will help to ensure you will always have an equal amount of bills and spending money each pay check.</ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item-header" id="headingFour">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                                    How do we get Due-Date reminder through 5 Dollar Application?<i class="fa fa-angle-down"></i>
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                                            <div class="t-p">
                                                <p>By adding all your reminder updates and monthly expenses on the application. The application reminds you of all the due dates so you never miss a payment. With the help of the 5 Dollar Bill Helper, you can make every payment on time.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="item-header" id="headingFour">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFour">
                                                    What are key features of 5 Dollar Bill?<i class="fa fa-angle-down"></i>
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="collapseFour" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                                            <div class="t-p">
                                                <li>Track all your expenses with just a click</li><br>
                                                <ul>The app tracks all your payments and offers you a monthly summary of expenditure. You can easily track the amount you have spent and where you have spent it.</ul>
                                                <li>Due Date Reminder</li><br>
                                                <ul>The application reminds you of all the due dates so you never miss a payment. With the help of the 5 Dollar Bill Helper, you can make every payment on time.</ul>
                                                <li>User-Friendly Interface</li><br>
                                                <ul>Managing your finances is tough, yet we make it easy for you. With just a few clicks you can check everything related to your bills and payments.</ul>
                                                <li>Balance your bills and spending!</li><br>
                                                <ul>Use The Bill Balancer to foresee upcoming payments and guarantee you will have enough money to pay the bills! The unique Bill Balancer splits payments by making sure your bills are balanced from pay check to pay check. It also looks at your spending money to balance how much you have to left to spend each pay period!</ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
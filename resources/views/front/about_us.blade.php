@extends('front.frontLayout.front_design')

@section('title', 'About Us')

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
                <img src="{{ url('public/frontend/image/about_text.png') }}" class="img-fluid">
                <div class="absolute_text">
                    <h4>About</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 pr-0">
           <div class="img_right_side">
               <img src="{{ url('public/frontend/image/about_mian_img.png') }}" class="img-fluid">
           </div>
        </div>
    </div>
</div>
<!-- /header -->
       
    <section>
        <div class="container">
            <div class="row py-2">
                <div class="col-xl-9 col-lg-11 mx-auto">
                    <div class="title_about">
                        <h4>Story Behind</h4>
                    </div>
                </div>
            </div>
            <div class="row py-5">
                <div class="col-xl-9  col-lg-11 mx-auto">
                    <div class="col-xl-6 col-lg-8 mx-auto">
                        <div class="story_detil">
                            <p>I was a young mom learning to pay bills. Traditional methods were not working for me. My bills kept increasing and I had trouble balancing my money from paycheck to paycheck. I would often forget that I wrote a check or spent money because I thought all my bills had cleared.
                            </p>
                            <p>I tried using other apps (X); But when I didn’t have the money to cover the bill, it was suggested that I get a loan! The(X) app, I used, also wanted me to hook up my bank account. At the time, I did not feel comfortable doing that. I didn’t understand this new highly integrated technology. The (X) app would not allow me to successfully use manual entries and would only update status unless I linked my bank account. I knew I had to do something.
                            </p>
                            <p>I quickly learned I needed to “Balance” my bills. I decided to create my own spreadsheet and keep it simple. I began working long hours and eventually had trouble updating my spreadsheet. It was too complicated. So, I added columns that I thought would help. I then began adding formulas to help with the calculations. I soon realized I needed color coding. I still had to do everything manually and sometimes the formulas would get messed up. But overall, I could keep making it work with some determination.</p>
                        </div>
                    </div>
                </div>
                <div class="round_left">
                    <img src="{{ url('public/frontend/image/left_round.png') }}" alt="image" class="img-fluid">
                </div>
            </div>
            <div class="row py-5">
                <div class="col-xl-9 col-lg-11  mx-auto">
                    <div class="col-xl-6 col-lg-8">
                        <div class="story_detil">
                            <h4>Here is what I learned</h4>
                            <p>Although most pay checks are weekly or bi-weekly, billing dates tend to fall into different pay periods. This causes an imbalance in spending money or a false sense of wealth. Monies are then spent that should have been allocated or set aside for upcoming bills. </p>
                            <p>When it comes time to pay those bills, we are then faced with the thought that we do not have enough money to pay them. It starts to feel like a constant struggle. After several formula mishaps, I decided to create an app that would encompass all the great things a spreadsheet could provide without the hassle of fixing formulas.</p>
                        </div>
                    </div>
                </div>
                <div class="round_right">
                    <img src="{{ url('public/frontend/image/right_round.png') }}" alt="image" class="img-fluid">
                </div>
            </div>
            <div class="row py-5">
                <div class="col-xl-9 col-lg-11  mx-auto">
                    <div class="col-xl-6 col-lg-8 ml-auto">
                        <div class="story_detil">
                            <h4>My solution</h4>
                            <p>I created the 5 Dollar Bill HelperTM with its trusty sidekick, the Bill BalancerTM mobile application. This app can stop this vicious cycle of financial imbalance. The user can view the current and upcoming pay periods with the bills listed. If an imbalance is noted, the user will have the ability to apply the Bill BalancerTM. The Bill BalancerTM splits monies from one pay period to another.  </p>
                            <p>This feature can be edited, and manually split payments can be entered. This app helps users to balance bills and spending money. Savings can be included, if desired and is recommended. The 5 Dollar Bill HelperTM was created using real life situations. Learn the right way to balance your bills. This is the easiest and most effective app to use. If an app is too complicated or cumbersome you won’t continue to use it. So, we developed the 5 Dollar Bill HelperTM with everyone in mind.</p>
                        </div>
                       
                    </div>
                </div>
                <div class="round_left">
                    <img src="{{ url('public/frontend/image/left_round.png') }}" alt="image" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <section>
        <div class="container py-3">
            <div class="row">
                <div class="col-xl-9 col-lg-11 col-md-11 col-sm-12 d-flex align-items-center justify-content-center mx-auto about_bg_purple flex-wrap">
                    <div class="col-xl-5 col-lg-5 col-md-5">
                        <div class="phn_section">
                            <img src="{{ url('public/frontend/image/about_Side_img_!.') }}png" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-7">
                        <div class="text_sectin_side">
                            <h4>Our Vision</h4>
                            <p>It is our vision that all may find personal wealth through financial balancing.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <Section>
        <div class="container py-3">
            <div class="row">
                <div class="col-xl-9 col-lg-11 col-md-11 col-sm-12 d-flex align-items-center justify-content-center mx-auto about_bg_cream flex-wrap">                    
                    <div class="col-xl-6 col-lg-6 col-md-7 order-md-1 order-2">
                        <div class="text_sectin_side left_side">
                            <h4>Our Mission</h4>
                            <p>To be the go-to mobile financial management application and the beginning of the #PersonalFinancialBalancingInitiative.</p>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-5 col-md-5 order-md-2 order-1">
                        <div class="phn_section_left">
                            <img src="{{ url('public/frontend/image/about_side_img_2.png') }}" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Section>

    <Section>
        <div class="container py-3">
            <div class="row">
                <div class="col-xl-9 col-lg-11 col-md-11 col-sm-12 d-flex align-items-center justify-content-center mx-auto  about_bg_sky flex-wrap">
                    <div class="col-xl-5 col-lg-5 col-md-5">
                        <div class="phn_section">
                            <img src="{{ url('public/frontend/image/about_side_img_3.png') }}" class="img-fluid">
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-7">
                        <div class="text_sectin_side">
                            <h4>Our Strategy</h4>
                            <p>Provide an inexpensive application, so everyone can learn financial balancing.</p>
                            <p>Through the #PersonalFinancialBalancingInitiative the 5 Dollar Bill HelperTM will waive the monthly fee for all high school students between the ages of 14 and 19. We believe that learning how to balance your money before the bills start piling up is essential for young adults to succeed in the adult world. This is our “#Pay it Forward” commitment.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </Section>

    <Section>
        <div class="container py-3">
            <div class="row">
                <div class="col-xl-9 col-lg-11 col-md-11 col-sm-12 d-flex align-items-center justify-content-center mx-auto about_bg_cream flex-wrap">                    
                    <div class="col-xl-6 col-lg-6 col-md-7 order-md-1 order-2">
                        <div class="text_sectin_side left_side">
                            <h4>Our Goals</h4>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
                        </div>
                    </div>
                    <div class="col-xl-5 col-lg-6 col-md-5 order-md-2 order-1">
                        <div class="phn_section_left">
                            <img src="{{ url('public/frontend/image/about_side_img_2.png') }}" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Section>



@endsection
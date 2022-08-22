@extends('front.frontLayout.front_design')

@section('title', 'Terms & Conditions')

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
    </div>
    
    <div class="">
       
        <section>
            <div class="container">
                <div class="row py-2">
                    <div class="col-xl-9 col-lg-11 mx-auto">
                        <div class="title_about">
                            <h4 class="text-center" style="padding: 20px;">{{ $cms->name }}</h4>
                            <!-- <h3 class="col-xl-6 col-lg-11 mx-auto"><Strong>The 5 Dollar Bill Helper...</Strong></h3> -->
                        </div>
                    </div>
                </div>
                <div class="row py-2">
                    <div class="col-xl-9 col-lg-11 mx-auto">
                        <!-- <div class="col-xl-6 col-lg-8 mx-auto"> -->
                            <!-- <div class="story_detil"> -->
                                <p><?=$cms->description?></p>
                            <!-- </div> -->
                        <!-- </div> -->
                    </div>
                </div>
                <!-- <div class="row py-2">
                    <div class="col-xl-9 col-lg-11  mx-auto">
                        <div class="col-xl-6 col-lg-8">
                            <div class="story_detil">
                                <h4>Here is what I learned</h4>
                                <p>Although most pay checks are weekly or bi-weekly, billing dates tend to fall into different pay periods. This causes an imbalance in spending money or a false sense of wealth. Monies are then spent that should have been allocated or set aside for upcoming bills. </p>
                                <p>When it comes time to pay those bills, we are then faced with the thought that we do not have enough money to pay them. It starts to feel like a constant struggle. After several formula mishaps, I decided to create an app that would encompass all the great things a spreadsheet could provide without the hassle of fixing formulas.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-9 col-lg-11  mx-auto">
                        <div class="col-xl-6 col-lg-8 ml-auto">
                            <div class="story_detil">
                                <h4>My solution</h4>
                                <p>I created the 5 Dollar Bill Helper<sup>TM</sup> with its trusty sidekick, the Bill Balancer<sup>TM</sup> mobile application. This app can stop this vicious cycle of financial imbalance. The user can view the current and upcoming pay periods with the bills listed. If an imbalance is noted, the user will have the ability to apply the Bill Balancer<sup>TM</sup>. The Bill BalancerTM splits monies from one pay period to another.  </p>
                                <p>This feature can be edited, and manually split payments can be entered. This app helps users to balance bills and spending money. Savings can be included, if desired and is recommended. The 5 Dollar Bill HelperTM was created using real life situations. Learn the right way to balance your bills. This is the easiest and most effective app to use. If an app is too complicated or cumbersome you won’t continue to use it. So, we developed the 5 Dollar Bill Helper<sup>TM</sup> with everyone in mind.</p>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </section>
    </div>
@endsection
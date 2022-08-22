@extends('front.frontLayout.front_design')

@section('title', 'Blogs')

@section('content')

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
                <img src="{{ url('public/frontend/image/blog_text.png') }}" class="img-fluid">
                <div class="absolute_text">
                    <h4>Blog</h4>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 pr-0">
            <div class="img_right_side">
                <img src="{{ url('public/frontend/image/blog_main_img.png') }}" class="img-fluid">
            </div>
        </div>
    </div>
</div>
<!-- /header -->

<section>
    <div class="container">
        <div class="col-xl-12 d-flex  flex-wrap">
            <div class="col-xl-4  col-lg-4 col-md-6 col-12  py-2">
                <a href="{{ url('blog-details') }}">
                    <div class="blog_box">
                        <div class="img_main">
                            <img src="{{ url('public/frontend/image/blog_1.png') }}" class="img-fluid">
                        </div>
                        <div class="detail_of_blog">
                            <h4>Lorem ipsum is simple dummy text of the printing and typesetting</h4>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when.</p>
                        </div>
                        <div class="py-3">
                            <button class="read_more_btn pr-3">Read More <i class="fas fa-chevron-right"></i></button>
                        </div>

                    </div>
                </a>
            </div>
            <div class="col-xl-4  col-lg-4 col-md-6 col-12  py-2 ">
                <a href="{{ url('blog-details') }}">
                    <div class="blog_box">
                        <div class="img_main">
                            <img src="{{ url('public/frontend/image/blog_1.png') }}" class="img-fluid">
                        </div>
                        <div class="detail_of_blog">
                            <h4>Lorem ipsum is simple dummy text of the printing and typesetting</h4>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when.</p>
                        </div>
                        <div class="py-3">
                            <button class="read_more_btn pr-3">Read More <i class="fas fa-chevron-right"></i></button>
                        </div>

                    </div>
                </a>
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-12 py-2 ">              
                <div class="blog_box pb-3">                      
                    <div class="detail_of_blog">
                        <h4 class="pt-0">Lorem ipsum is simple dummy text of the printing and typesetting</h4>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when.</p>
                    </div>
                    <div class="py-3">
                        <button class="read_more_btn pr-3">Read More <i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>              
                <div class="blog_box py-3">                      
                    <div class="detail_of_blog">
                        <h4>Lorem ipsum is simple dummy text of the printing and typesetting</h4>
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when.</p>
                    </div>
                    <div class="py-3">
                        <button class="read_more_btn pr-3">Read More <i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>             
            </div>
            <div class="col-xl-4 col-lg-4 col-md-6 col-12   py-2">
                <a href="{{ url('blog-details') }}">
                    <div class="blog_box">
                        <div class="img_main">
                            <img src="{{ url('public/frontend/image/blog_1.png') }}" class="img-fluid">
                        </div>
                        <div class="detail_of_blog">
                            <h4>Lorem ipsum is simple dummy text of the printing and typesetting</h4>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when.</p>
                        </div>
                        <div class="py-3">
                            <button class="read_more_btn pr-3">Read More <i class="fas fa-chevron-right"></i></button>
                        </div>

                    </div>
                </a>
            </div>
            <div class="col-xl-4  col-lg-4 col-md-6 col-12  py-2 ">
                <a href="{{ url('blog-details') }}">
                    <div class="blog_box">
                        <div class="img_main">
                            <img src="{{ url('public/frontend/image/blog_1.png') }}" class="img-fluid">
                        </div>
                        <div class="detail_of_blog">
                            <h4>Lorem ipsum is simple dummy text of the printing and typesetting</h4>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when.</p>
                        </div>
                        <div class="py-3">
                            <button class="read_more_btn pr-3">Read More <i class="fas fa-chevron-right"></i></button>
                        </div>

                    </div>
                </a>
            </div>
            <div class="col-xl-4  col-lg-4 col-md-6 col-12  py-2">
                <a href="{{ url('blog-details') }}">
                    <div class="blog_box">
                        <div class="img_main">
                            <img src="{{ url('public/frontend/image/blog_1.png') }}" class="img-fluid">
                        </div>
                        <div class="detail_of_blog">
                            <h4>Lorem ipsum is simple dummy text of the printing and typesetting</h4>
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when.</p>
                        </div>
                        <div class="py-3">
                            <button class="read_more_btn pr-3">Read More <i class="fas fa-chevron-right"></i></button>
                        </div>

                    </div>
                </a>
            </div>
        </div> 
    </div>          

   
</section>

@endsection
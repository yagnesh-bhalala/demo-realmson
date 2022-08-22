@extends('front.frontLayout.front_design')

@section('title', 'Blog Details')

@section('content')

<!-- header -->
<div class="main_header_Section blog_detail">
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
        <div class="col-xl-6 col-lg-7 col-md-11 ">
            <div class="text_head detail_text text-center">
                <h4 class="py-4">Lorem ipsum is simple dummy text of the printing and typesetting</h4>
                <h5>Share</h5>
                <div class="iocn_header pt-3 pb-5">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>                   
                </div>
            <!-- <div class="img_animate_arrow ">
                <img src="{{ url('public/frontend/image/arrow_animate.png') }}" class="img-fluid">
            </div> -->
        </div>       
    </div>
</div>
</div>
<!-- /header -->

<!-- <div class="bg_round" style="margin-top:35rem"> -->
    

        <div class="conatainer">
            <div class="">
                <div class="col-xl-12">
                    <div class="col-xl-9 col-lg-11 col-md-12 col-12 d-flex  justify-content-center mx-auto flex-wrap">
                        <div class="col-xl-8 col-lg-8 col-md-8 col-12 pl-0">
                            <div class="detail_blog">
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>
                                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown</p>
                                <h4>Lorem ipsum is simple dummy textof the printing and typesetting</h4>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-12 pr-0 ">
                            <div class="img_blog text-right">
                                <img src="{{ url('public/frontend/image/blog_detail_img.png') }}" class="img-fluid">
                            </div> 
                        </div>
                    </div>
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                    <div class="col-xl-9 col-lg-11 col-md-12 col-12 mx-auto detail_blog">
                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.</p>
                    </div>
                </div>
            </div>
        </div>
   
<!-- </div> -->

@endsection
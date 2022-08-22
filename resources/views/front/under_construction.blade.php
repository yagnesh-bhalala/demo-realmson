@extends('front.frontLayout.front_design')



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
<!-- /header -->


<div class="bg_round">
    <div class="img_bg_about text-center">
        <img src="{{ url('public/frontend/image/underconstruction.png') }}" class="img-fluid">
    </div>
</div>

@endsection
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to 5dollar | @yield('title')</title>

    <!-- Favicon -->    
    <link rel="shortcut icon" type="image/jpg" href="{{ url('public/frontend/image/favicon.png') }}"> 


    <!-- font links -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@1,300;1,400&family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;1,200;1,300;1,400;1,600&family=Pacifico&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,700;0,800;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">


    <!-- css links -->
    <link href="{{ url('public/frontend/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ url('public/frontend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css" integrity="sha512-yHknP1/AwR+yx26cB1y0cjvQUMvEa2PFzt1c9LlS4pRQ5NOTZFWbhBig+X9G9eYW/8m0/4OXNx8pxJ6z57x0dw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ url('public/frontend/css/owl.carousel.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('public/frontend/css/owl.theme.default.min.css') }}">
    <link href="{{ url('public/frontend/css/style.css') }}" rel="stylesheet">
    <link href="{{ url('public/frontend/css/responsive.css') }}" rel="stylesheet">
</head>

<body>

    @yield('content')

    @include('front.frontLayout.front_footer')

</body>
<!-- !-- js links -->
<script src="{{ url('public/frontend/js/jquery.min.js') }}"></script>
<script src="{{ url('public/frontend/js/popper.min.js') }}"></script>
<script src="{{ url('public/frontend/js/bootstrap.min.js') }}"></script>
<script src="{{ url('public/frontend/js/owl.carousel.min.js') }}"></script>

<!--midddal_sec_main  -->
<script>
    var midddal_sec_main = $('.midddal_sec_main');
        midddal_sec_main.owlCarousel({
        loop:false,
        nav:true,
        navText: ["<img src='public/frontend/image/left_btn.png'class='left-arrow'>",
                        "<img src='public/frontend/image/right_btn.png'class='right-arrow'>"],
        center: false,
        items:1,
        margin:10,
        dots:false,
            onDragged: activeSlide,
        onInitialized: activeSlide,
        responsive:{
                0:{
                    items:1
                },
                600:{
                    items:2
                },
                768:{
                    items:2
                },
                1024:{
                    items:2
                },
                1440:{
                    items:3
                }
        }
    });
    function activeSlide(){
    $(".midddal_sec_main").find('.active_slide').removeClass('active_slide');
    $curr = $(".midddal_sec_main").find(".owl-item.active.center").addClass('active_slide');
    $curr.prev('.owl-item.active').addClass('active_slide');
        $curr.next('.owl-item.active').addClass('active_slide');
    }
</script>

</html>
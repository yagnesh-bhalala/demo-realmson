
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ url('public/frontend/image/logo_main.png') }}" alt="logo" class="img-fluid">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fas fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse justify-content-end pr-3" id="navbarNavDropdown">
            <ul class="navbar-nav">
                <li class="nav-item  px-2 {{ (Request::path() == '/') ? 'active':'' }}">
                    <a class="nav-link" href="{{ url('/') }}">Home <span class="sr-only">(current)</span></a>
                </li>

                <li class="nav-item  px-2 {{ (Request::path()== 'about-us') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/about-us') }}">About Us</a>
                </li>

                <li class="nav-item px-2 {{ (Request::path()== 'faq') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/faq') }}">FAQs</a>
                </li>

                <li class="nav-item px-2 {{ (Request::path()== 'pricing') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/pricing') }}">Pricing</a>
                </li>

                <li class="nav-item px-2 {{ (Request::path()== 'blogs' || Request::path()== 'blog-details') ? 'active' : '' }}">
                    <a class="nav-link" href="{{ url('/blogs') }}">Blog</a>
                </li>
            </ul>
            <div class="button_header d-flex align-items-center ">
                <button class="btn btn_create mx-2">My Account</button>
                <button class="btn btn_create active ml-2">Create Account</button>
            </div>
        </div>
    </nav>

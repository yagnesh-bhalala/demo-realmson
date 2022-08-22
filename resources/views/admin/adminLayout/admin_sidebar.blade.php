<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/admin/dashboard') }}" class="brand-link">
        <img src="{{ url('public/img/mailLogo.png') }}" alt="5$ Logo" class="brand-image img-circle elevation-3">
        <span class="brand-text font-weight-light">5 Dollar</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ url('public/backend/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    @if(Auth::check())
                        {{ ucwords(Auth::User()->firstName) }}
                    @endif
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
               @if(Session::get('page')=="dashboard")
                    <?php $active = "active"; ?>
                @else
                    <?php $active = ""; ?>
                @endif
                <li class="nav-item has-treeview menu-open">
                    <a href="{{ url('/admin/dashboard') }}" class="nav-link {{ $active }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>
                </li>
                @if(Session::get('page')=="users")
                    <?php $active = "active"; ?>
                @else
                    <?php $active = ""; ?>
                @endif
                <li class="nav-item has-treeview">
                    <a href="{{ url('/admin/get-users') }}" class="nav-link {{ $active }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>
                @if(Session::get('page')=="mainCategory")
                    <?php $active = "active"; ?>
                @else
                    <?php $active = ""; ?>
                @endif
                <li class="nav-item has-treeview">
                    <a href="{{ url('/admin/get-main-category') }}" class="nav-link {{ $active }}">
                        <i class="nav-icon fas fa-list"></i>
                        <p>
                            Main Category
                        </p>
                    </a>
                </li>
                @if(Session::get('page')=="cms" || Session::get('page')=="faq" || Session::get('page')=="blog" || Session::get('page')=="apiresponse")
                    <?php $active = "active"; ?>
                @else
                    <?php $active = ""; ?>
                @endif
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link {{ $active }}">
                        <i class="nav-icon fas fa-laptop-code"></i>
                        <p>
                            CMS Master
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        @if(Session::get('page')=="cms")
                            <?php $active = "active"; ?>
                        @else
                            <?php $active = ""; ?>
                        @endif
                        <li class="nav-item has-treeview">
                            <a href="{{ url('/admin/get-cms') }}" class="nav-link {{ $active }}">
                                <i class="nav-icon fas fa-laptop-code"></i>
                                <p>
                                    CMS
                                </p>
                            </a>
                        </li>
                        @if(Session::get('page')=="faq")
                            <?php $active = "active"; ?>
                        @else
                            <?php $active = ""; ?>
                        @endif
                        <li class="nav-item has-treeview">
                            <a href="{{ url('/admin/get-faqs') }}" class="nav-link {{ $active }}">
                                <i class="nav-icon fas fa-question-circle"></i>
                                <p>
                                    FAQ
                                </p>
                            </a>
                        </li>
                        @if(Session::get('page')=="apiresponse")
                            <?php $active = "active"; ?>
                        @else
                            <?php $active = ""; ?>
                        @endif
                        <li class="nav-item has-treeview">
                            <a href="{{ url('/admin/get-api-responses') }}" class="nav-link {{ $active }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>
                                    Api Response
                                </p>
                            </a>
                        </li>
                        @if(Session::get('page')=="blog")
                            <?php $active = "active"; ?>
                        @else
                            <?php $active = ""; ?>
                        @endif
                        <li class="nav-item has-treeview">
                            <a href="{{ url('/admin/get-blogs') }}" class="nav-link {{ $active }}">
                                <i class="nav-icon fas fa-blog"></i>
                                <p>
                                    Blog
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                @if(Session::get('page')=="appfeedback")
                    <?php $active = "active"; ?>
                @else
                    <?php $active = ""; ?>
                @endif
                <li class="nav-item has-treeview">
                    <a href="{{ url('/admin/get-app-feedback') }}" class="nav-link {{ $active }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>
                            App Feedback
                        </p>
                    </a>
                </li>

                
                <!-- <li class="nav-header">EXAMPLES</li>
                <li class="nav-item">
                    <a href="pages/calendar.html" class="nav-link">
                        <i class="nav-icon far fa-calendar-alt"></i>
                        <p>
                            Calendar
                            <span class="badge badge-info right">2</span>
                        </p>
                    </a>
                </li> -->
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
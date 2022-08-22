<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Five Dollar Bill | Admin Panel</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ url('public/backend/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ url('public/backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ url('public/backend/css/adminlte.min.css') }}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <!-- Favicon -->    
  <link rel="shortcut icon" type="image/jpg" href="{{ url('public/frontend/image/favicon.png') }}">
  <style>
body {
  background-image: url('public/frontend/image/image3.jpg');
}
</style>
</head>
<body class="login-page">

<div class="login-box">
  <div class="login-logo">
    <a href="{{ url('/admin') }}"><b>Five Dollar Bill Helper<sup>TM</sup> </b>Admin</a>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>
      @if(Session::has('error_message1'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>{{ Session::get('error_message') }}</strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ url('/admin') }}" method="post">
      @csrf

        <div class="input-group mb-3">
          <input type="text" name="email" id="email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" id="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery UI 1.11.4
<script src="{{ url('public/backend/plugins/jquery-ui/jquery-ui.min.js') }}"></script> -->
<!-- jQuery -->
<script src="{{ url('public/backend/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ url('public/backend/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ url('public/backend/js/adminlte.min.js') }}"></script>
<!-- @if(Session::has('error_message'))
<script>
  $(document).Toasts('create', {
  title: 'Toast Title',
  body: '{{ Session::get('success_message') }}',
  position: 'bottomRight'
})
</script>
@endif -->
<script src="{{ url('public/backend/js/bootstrap-notify.js') }}"></script>
@if(Session::has('error_message'))
  <script>
      $(document).ready(function() {
        jQuery.notify({
          message: "{{ Session::get('error_message') }}",
        }, {
          type: 'danger',
          delay: 5000,
          allow_dismiss: true,
        });
      });
    </script>
@endif
@if(Session::has('success_message'))
  <script>
      $(document).ready(function() {
        jQuery.notify({
          message: "{{ Session::get('success_message') }}",
        }, {
          type: 'success',
          delay: 5000,
          allow_dismiss: true,
        });
      });
    </script>
@endif
</body>
</html>

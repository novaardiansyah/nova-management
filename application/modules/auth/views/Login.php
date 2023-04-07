<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta http-equiv="Set-Cookie" content="HttpOnly;Secure;SameSite=None" />
  <title>Login</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <?php $this->load->view('layouts/main/Favicon') ?>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= adminlte('plugins/fontawesome-free/css/all.min.css') ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= adminlte('plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= adminlte('dist/css/adminlte.min.css') ?>">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form action="<?= base_url('auth/attempt-login') ?>" method="post">
          <div class="input-group mb-3">
            <input type="text" class="form-control" name="username_or_email" placeholder="Username or Email" />
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fa fa-fw fa-envelope"></span>
              </div>
            </div>
          </div>

          <div class="input-group mb-3">
            <input type="password" class="form-control" name="password" placeholder="Password" />
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fa fa-fw fa-eye" style="cursor: pointer;" onclick="return toggle_password(event)"></span>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4">
              <div class="captcha mb-2">
                <img src="" alt="captcha" class="mr-2 image captcha" style="display: none;" />
              </div>
            </div>
            <!-- /.col-md -->
            <div class="col-md">
              <div class="input-group mb-3">
                <input type="text" class="form-control" name="user_captcha" placeholder="Captcha" onkeyup="return enter_captcha(event)" />
                <div class="input-group-append">
                  <div class="input-group-text">
                    <i class="fa fa-fw fa-sync-alt" onclick="return generate_captcha()" style="cursor: pointer;"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-8">
              <div class="icheck-primary">
                <input type="checkbox" id="remember" name="remember" />
                <label for="remember">Remember Me</label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block" onclick="return attempt_login(event)">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <!-- <p class="mb-1">
          <a href="#" onclick="return (e) => e.preventDefault()">I forgot my password</a>
        </p>
        <p class="mb-0">
          <a href="<?= base_url('auth/register') ?>" class="text-center">Register a new account</a>
        </p> -->
      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="<?= adminlte('plugins/jquery/jquery.min.js') ?>"></script>
  <!-- Bootstrap 4 -->
  <script src="<?= adminlte('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <!-- AdminLTE App -->
  <script src="<?= adminlte('dist/js/adminlte.min.js') ?>"></script>
  <script>
    const config = {
      csrf_token_name: '<?= $this->security->get_csrf_token_name() ?>',
      csrf_hash: '<?= $this->security->get_csrf_hash() ?>',
      base_url: '<?= base_url() ?>',
    }
  </script>
  <!-- SweetAlert -->
  <script src="<?= assets('plugins/template-admin/plugins/sweetalert2/sweetalert2.all.min.js'); ?>"></script>
  <!-- Core Js -->
  <script src="<?= assets('js/core/utils.js' . version()) ?>"></script>
  <!-- Js -->
  <script src="<?= assets('js/auth/login.js' . version()) ?>"></script>
</body>
</html>
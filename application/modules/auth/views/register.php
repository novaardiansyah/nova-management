<div class="row h-100">
  <div class="col-lg-5 col-12">
    <div id="auth-left">
      <div class="auth-logo">
        <a href="<?= base_url('auth/register'); ?>"><img src="<?= base_url('assets/images/mainLogo/' . $mainLogo['logo']->path); ?>" alt="Logo"></a>
      </div>
      <h1 class="auth-title">Sign Up</h1>
      <p class="auth-subtitle mb-5">Input your data to register to our website.</p>
      
      <form action="<?= base_url('auth/validateRegister'); ?>" method="post" enctype="multipart/form-data" id="form-register">
        <div class="form-group position-relative has-icon-left mb-4">
          <input type="email" class="form-control form-control-xl" id="l_email" name="email"  placeholder="Email" />
          <div class="invalid-feedback email"></div>

          <div class="form-control-icon">
            <i class="bi bi-envelope"></i>
          </div>
        </div>
        <!-- /.form-group -->

        <div class="form-group position-relative has-icon-left mb-4">
          <input type="text" class="form-control form-control-xl" id="l_username" name="username" placeholder="Username" />
          <div class="invalid-feedback username"></div>

          <div class="form-control-icon">
            <i class="bi bi-person"></i>
          </div>
        </div>
        <!-- /.form-group -->

        <div class="form-group position-relative has-icon-left mb-4">
          <input type="password" class="form-control form-control-xl" id="l_password" name="password" placeholder="Password" />
          <div class="invalid-feedback password"></div>

          <div class="form-control-icon">
            <i class="bi bi-shield-lock"></i>
          </div>
        </div>
        <!-- /.form-group -->

        <div class="form-group position-relative has-icon-left mb-4">
          <input type="password" class="form-control form-control-xl" id="l_confirmPassword" name="confirmPassword" placeholder="Confirm Password" />
          <div class="invalid-feedback confirmPassword"></div>

          <div class="form-control-icon">
            <i class="bi bi-shield-lock"></i>
          </div>
        </div>
        <!-- /.form-group -->

        <button type="button" class="btn btn-primary btn-block btn-lg shadow-lg" onclick="return register()">Sign Up</button>
      </form>
      
      <div class="text-center mt-5 text-lg fs-4">
        <p class='text-gray-600'>Already have an account? <a href="<?= base_url('auth'); ?>" class="font-bold">Log in</a>.</p>
      </div>
    </div>
  </div>
  <div class="col-lg-7 d-none d-lg-block">
    <div id="auth-right"></div>
  </div>
</div>
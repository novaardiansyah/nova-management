<?php 
  $user = getSession('user') ?? [];
?>

<section class="section">
  <div class="card">
    <div class="card-content">
      <div class="card-body">
        <h4 class="card-title">Update Profile</h4>
        <p class="card-text">Complete your data to enjoy all the features.</p>
        <form action="<?= base_url('account/profile/updateProfile'); ?>" class="form" method="post" enctype="multipart/form-data">
          <div class="form-body">
            <?php if ((int) $user->isActive == 0) : ?>
              <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i> 
                Your account is not yet active, <a href="#" class="badge bg-primary">click here</a> to resend the activation email.
              </div>
            <?php endif; ?>

            <div class="form-group mb-3">
              <label for="e_username" class="sr-only mb-2">Username <span class="text-danger">*</span></label>
              <input type="text" id="e_username" name="username" class="form-control" placeholder="Please enter value here.." value="<?= $user->username; ?>" disabled />

              <div class="invalid-feedback username"></div>
            </div>
            <!-- /.form-group -->

            <div class="form-group mb-3">
              <label for="e_email" class="sr-only mb-2">Email <span class="text-danger">*</span></label>
              <input type="text" id="e_email" name="email" class="form-control" placeholder="Please enter value here.." value="<?= $user->email; ?>" disabled />

              <div class="invalid-feedback email"></div>
            </div>
            <!-- /.form-group -->

            <div class="form-group mb-3">
              <label for="e_nameRole" class="sr-only mb-2">Role Access <span class="text-danger">*</span></label>
              <input type="text" id="e_nameRole" name="nameRole" class="form-control" placeholder="Please enter value here.." value="<?= $user->nameRole; ?>" disabled />

              <div class="invalid-feedback nameRole"></div>
            </div>
            <!-- /.form-group -->

            <?php if ((int) $user->isActive == 1) : ?>
              <div class="form-group mb-3">
                <label for="e_isActive" class="sr-only mb-2 d-block">Status <span class="text-danger">*</span></label>
                
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="isActive" id="a_isActive1" value="1" <?= (int) $user->isActive == 1 ? 'checked' : '' ?> disabled />
                  <label class="form-check-label" for="a_isActive1">Active</label>
                </div>
                <div class="form-check form-check-inline">
                  <input class="form-check-input" type="radio" name="isActive" id="a_isActive2" value="0" <?= (int) $user->isActive == 0 ? 'checked' : '' ?> disabled />
                  <label class="form-check-label" for="a_isActive2">Non-Active</label>
                </div>

                <div class="invalid-feedback isActive"></div>
              </div>
              <!-- /.form-group -->

              <div class="form-group mb-3">
                <label for="e_activate_at" class="sr-only mb-2">Member Since <span class="text-danger">*</span></label>
                <input type="text" id="e_activate_at" name="activate_at" class="form-control" placeholder="Please enter value here.." value="<?= format_date($user->activate_at, 'F d, Y'); ?>" disabled />

                <div class="invalid-feedback activate_at"></div>
              </div>
              <!-- /.form-group -->
            <?php endif; ?>
          </div>

          <!-- <div class="form-actions d-flex justify-content-end"> -->
          <div class="form-actions d-none justify-content-end">
            <button type="button" class="btn btn-primary me-1">Submit</button>
            <button type="button" class="btn btn-light-primary" onclick="return ReloadPage()">Refresh</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>
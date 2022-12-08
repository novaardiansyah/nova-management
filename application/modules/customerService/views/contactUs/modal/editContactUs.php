<div class="modal fade" id="editContactUs" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addContactUsLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addContactUsLabel">Edit Contact Us</h1>
        <button type="button" class="btn-close" onclick="return toggleModal('editContactUs', 'close')"></button>
      </div>
      <div class="modal-body">
        <div class="d-flex d-none justify-content-center align-items-center loader form-editContactUs">
          <img src="<?= base_url('assets/mazer/assets/images/svg-loaders/audio.svg'); ?>" alt="loader" class="img-fluid me-2" style="max-width: 200px;" />  
          <img src="<?= base_url('assets/mazer/assets/images/svg-loaders/audio.svg'); ?>" alt="loader" class="img-fluid" style="max-width: 200px;" />         
        </div>

        <form action="<?= base_url('customerService/contactUs/updateContactUs'); ?>" method="post" enctype="multipart/form-data" id="form-editContactUs" class="pe-4 d-none">
          <div class="mb-3 row">
            <label for="e_name" class="col-sm-2 col-form-label text-end">Name <span class="text-danger">*</span></label>
            <div class="col-sm">
              <input type="text" class="form-control autohide-invalid" id="e_name" name="name" placeholder="Please enter value here.." />
              <div class="invalid-feedback name"></div>
            </div>
          </div>

          <div class="mb-3 row">
            <label for="e_email" class="col-sm-2 col-form-label text-end">Email <span class="text-danger">*</span></label>
            <div class="col-sm">
              <input type="text" class="form-control autohide-invalid" id="e_email" name="email" placeholder="Please enter value here.." />
              <div class="invalid-feedback email"></div>
            </div>
          </div>

          <div class="mb-3 row">
            <label for="e_phone" class="col-sm-2 col-form-label text-end">Phone <span class="text-danger d-none">*</span></label>
            <div class="col-sm">
              <input type="text" class="form-control autohide-invalid" id="e_phone" name="phone" placeholder="Please enter value here.." />
              <div class="invalid-feedback phone"></div>
            </div>
          </div>

          <div class="mb-3 row">
            <label for="e_message" class="col-sm-2 col-form-label text-end">Message <span class="text-danger">*</span></label>
            <div class="col-sm">
              <textarea class="form-control autohide-invalid" name="message" id="e_message" rows="3" placeholder="Please enter value here.."></textarea>
              <div class="invalid-feedback message"></div>
            </div>
          </div>

          <div class="mb-3 row">
            <label for="e_isActive1" class="col-sm-2 col-form-label text-end">Status <span class="text-danger">*</span></label>
            <div class="col-sm d-flex align-items-center">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="isActive" id="e_isActive1" value="1" checked />
                <label class="form-check-label" for="e_isActive1">Active</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="isActive" id="e_isActive2" value="0" />
                <label class="form-check-label" for="e_isActive2">Non-Active</label>
              </div>

              <div class="invalid-feedback isActive"></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" onclick="return toggleModal('editContactUs', 'close')">Close</button>
        <button type="button" class="btn btn-sm btn-primary" onclick="return updateContactUs('editContactUs')">Save</button>
      </div>
    </div>
  </div>
</div>
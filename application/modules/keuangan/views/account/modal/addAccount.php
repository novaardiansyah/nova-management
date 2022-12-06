<?php 
  $currency    = $currency['status'] ? $currency['data']['list'] : [];
  $typeAccount = $typeAccount['status'] ? $typeAccount['data']['list'] : [];
?>

<div class="modal fade" id="addAccount" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addDataLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addDataLabel">Add Account</h1>
        <button type="button" class="btn-close" onclick="return toggleModal('addAccount', 'close')"></button>
      </div>
      <div class="modal-body">
        <div class="d-flex d-none justify-content-center align-items-center loader form-addAccount">
          <img src="<?= base_url('assets/mazer/assets/images/svg-loaders/audio.svg'); ?>" alt="loader" class="img-fluid me-2" style="max-width: 200px;" />  
          <img src="<?= base_url('assets/mazer/assets/images/svg-loaders/audio.svg'); ?>" alt="loader" class="img-fluid" style="max-width: 200px;" />         
        </div>

        <form action="<?= base_url('keuangan/account/storeAccount'); ?>" method="post" enctype="multipart/form-data" id="form-addAccount" class="pe-4 d-none">
          <div class="mb-3 row">
            <label for="a_name" class="col-sm-2 col-form-label text-end">Name <span class="text-danger">*</span></label>
            <div class="col-sm">
              <input type="text" class="form-control autohide-invalid" id="a_name" name="name" placeholder="Please enter value here.." />
              <div class="invalid-feedback name"></div>
            </div>
          </div>

          <div class="mb-3 row">
            <label for="a_idCurrency" class="col-sm-2 col-form-label text-end">Currency <span class="text-danger">*</span></label>
            <div class="col-sm">
              <select class="form-select custom-select autohide-invalid" id="a_idCurrency" name="idCurrency" default="MzQ=;IDR">
                <option value="">Please choose value here..</option>

                <?php foreach ($currency AS $row => $value) : ?>
                  <option value="<?= $value->id . ';' . $value->short; ?>"><?= $value->f1_name; ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback idCurrency"></div>
            </div>
          </div>
          
          <div class="mb-3 row">
            <label for="a_idType" class="col-sm-2 col-form-label text-end">Type <span class="text-danger">*</span></label>
            <div class="col-sm">
              <select class="form-select custom-select autohide-invalid" id="a_idType" name="idType" default="">
                <option value="">Please choose value here..</option>

                <?php foreach ($typeAccount AS $row => $value) : ?>
                  <option value="<?= $value->id; ?>"><?= $value->name; ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback idType"></div>
            </div>
          </div>

          <div class="mb-3 row">
            <label for="a_amount" class="col-sm-2 col-form-label text-end">Amount <span class="text-danger">*</span></label>
            <div class="col-sm input-group">
              <span class="input-group-text amount">$</span>
              <input type="text" class="form-control autohide-invalid" id="a_amount" name="amount" placeholder="Please enter value here.." maxlength="10" onkeypress="return onlyNumber(event)" />
              <div class="invalid-feedback amount"></div> 
            </div>
          </div>

          <div class="mb-3 row">
            <label for="a_logo" class="col-sm-2 col-form-label text-end">Logo <span class="text-danger">*</span></label>
            <div class="col-sm">
              <input type="file" class="form-control image-preview autohide-invalid" id="a_logo" name="logo" accept=".jpg, .jpeg, .png" placeholder="Please enter value here.." />
              <div class="invalid-feedback logo"></div>
              
              <div class="toggle-preview mt-2 d-none logo">
                <img src="<?= base_url('assets/images/financeLogo/default-logo-finance.png'); ?>" alt="preview" class="img-fluid img-thumbnail" style="max-width: 120px;" />
              </div>
            </div>
          </div>

          <div class="mb-3 row">
            <label for="a_isActive1" class="col-sm-2 col-form-label text-end">Status <span class="text-danger">*</span></label>
            <div class="col-sm d-flex align-items-center">
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="isActive" id="a_isActive1" value="1" checked />
                <label class="form-check-label" for="a_isActive1">Active</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="isActive" id="a_isActive2" value="0" />
                <label class="form-check-label" for="a_isActive2">Non-Active</label>
              </div>

              <div class="invalid-feedback isActive"></div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-secondary" onclick="return toggleModal('addAccount', 'close')">Close</button>
        <button type="button" class="btn btn-sm btn-primary" onclick="return storeAccount('addAccount')">Save</button>
      </div>
    </div>
  </div>
</div>
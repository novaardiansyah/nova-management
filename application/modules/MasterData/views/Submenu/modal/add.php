<?php 
  $menuData = $menuData['status'] ? $menuData['data']['menu'] : [];
?>

<div class="modal fade" id="addData" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addDataLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="addDataLabel">Add Data</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" data-bs-target="#addData" aria-label="Close" onclick="return formModalReset('form-addData')"></button>
      </div>
      <div class="modal-body">
        <form action="<?= base_url('masterData/submenu/addData'); ?>" method="post" enctype="multipart/form-data" id="form-addData" class="pe-4">
          <div class="mb-3 row">
            <label for="a_idMenu" class="col-sm-2 col-form-label text-end">Menu <span class="text-danger">*</span></label>
            <div class="col-sm">
              <select class="form-select custom-select autohide-invalid" id="a_idMenu" name="idMenu">
                <option value="">Please select value here..</option>
                <?php foreach ($menuData as $key => $value) : ?>
                  <option value="<?= $value->id; ?>"><?= $value->name; ?></option>
                <?php endforeach; ?>
              </select>
              <div class="invalid-feedback idMenu"></div>
            </div>
          </div>

          <div class="mb-3 row">
            <label for="a_name" class="col-sm-2 col-form-label text-end">Submenu <span class="text-danger">*</span></label>
            <div class="col-sm">
              <input type="text" class="form-control autohide-invalid" id="a_name" name="name" placeholder="Please enter value here.." />
              <div class="invalid-feedback name"></div>
            </div>
          </div>

          <div class="mb-3 row">
            <label for="a_link" class="col-sm-2 col-form-label text-end">Link <span class="text-danger d-none">*</span></label>
            <div class="col-sm input-group">
              <span class="input-group-text">/</span>
              <input type="text" class="form-control autohide-invalid" id="a_link" name="link" placeholder="Please enter value here.." />
              <div class="invalid-feedback link"></div>
            </div>
          </div>

          <div class="mb-3 row">
            <label for="a_sortOrder" class="col-sm-2 col-form-label text-end">Sort Order <span class="text-danger">*</span></label>
            <div class="col-sm">
              <input type="text" class="form-control autohide-invalid" id="a_sortOrder" name="sortOrder" placeholder="Please enter value here.." value="0" min="0" minlength="1" maxlength="5" />
              <div class="invalid-feedback sortOrder"></div>
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
        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" data-bs-target="#addData" id="addData-close" onclick="return formModalReset('form-addData')">Close</button>
        <button type="button" class="btn btn-sm btn-primary" onclick="return addData()">Save</button>
      </div>
    </div>
  </div>
</div>
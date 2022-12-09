<section class="section">
  <div class="card">
    <div class="card-header">
      <button type="button" class="btn btn-success btn-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#addData" onclick="return formModalReset()" id="addData-show">
        <i class="bi bi-plus-square"></i>
        <span class="d-none d-lg-inline">Add</span>
      </button>
    </div>
    <div class="card-body menuList">
      <table class="table table-striped" id="menuList">
        <thead>
          <tr>
            <th>No.</th>
            <th>Name</th>
            <th>Icon</th>
            <th>Link</th>
            <th>Sort Order</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="menuList">
          <!-- content here from ajax -->
        </tbody>
      </table>

      <!-- Loader (Start) -->
      <div class="d-flex justify-content-center align-items-center loader-table menuList">
        <img src="<?= base_url('assets/mazer/assets/images/svg-loaders/audio.svg'); ?>" alt="loader" class="img-fluid me-2" style="max-width: 200px;" />  
        <img src="<?= base_url('assets/mazer/assets/images/svg-loaders/audio.svg'); ?>" alt="loader" class="img-fluid" style="max-width: 200px;" />         
      </div>

      <div class="d-flex d-none justify-content-center align-items-center no-data-table menuList">
        <img src="<?= base_url('assets/images/admin/table-no-data-found.gif'); ?>" alt="no-data-found" class="img-fluid me-2" style="max-width: 350px;" />  
      </div>
      <!-- Loader (End) -->
    </div>
  </div>
</section>

<?php $this->load->view('masterData/menu/modal/add'); ?>
<?php $this->load->view('masterData/menu/modal/edit'); ?>
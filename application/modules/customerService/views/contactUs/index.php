<section class="section tab-content contactUsList">
  <div class="toggle-modal" id="wrapper-toggle-modal"></div>

  <div class="card">
    <div class="card-header">
      <button type="button" class="btn btn-success btn-sm text-nowrap" onclick="return addContactUs('addContactUs')">
        <i class="bi bi-plus-square"></i>
        <span class="d-none d-lg-inline">Add</span>
      </button>
    </div>
    <div class="card-body contactUsList">
      <table class="table table-striped align-middle" id="contactUsList">
        <thead>
          <tr>
            <th>No.</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Message</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="contactUsList">
          <!-- content here from ajax -->
        </tbody>
      </table>

      <!-- Loader (Start) -->
      <div class="d-flex justify-content-center align-items-center loader-table contactUsList">
        <img src="<?= base_url('assets/mazer/assets/images/svg-loaders/audio.svg'); ?>" alt="loader" class="img-fluid me-2" style="max-width: 200px;" />  
        <img src="<?= base_url('assets/mazer/assets/images/svg-loaders/audio.svg'); ?>" alt="loader" class="img-fluid" style="max-width: 200px;" />         
      </div>

      <div class="d-flex d-none justify-content-center align-items-center no-data-table contactUsList">
        <img src="<?= base_url('assets/images/admin/table-no-data-found.gif'); ?>" alt="no-data-found" class="img-fluid me-2" style="max-width: 350px;" />  
      </div>
      <!-- Loader (End) -->
    </div>
  </div>
</section>

<?php $this->load->view('customerService/contactUs/modal/addContactUs'); ?>
<?php $this->load->view('customerService/contactUs/modal/editContactUs'); ?>
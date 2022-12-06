<section class="section tab-content accountList">
  <div class="toggle-modal" id="wrapper-toggle-modal"></div>

  <div class="card">
    <div class="card-header">
      <button type="button" class="btn btn-success btn-sm text-nowrap" onclick="return addAccount('addAccount')">
        <i class="bi bi-plus-square"></i>
        <span class="d-none d-lg-inline">Add</span>
      </button>
    </div>
    <div class="card-body accountList">
      <table class="table table-striped align-middle" id="accountList">
        <thead>
          <tr>
            <th>No.</th>
            <th>Name</th>
            <th>Currency</th>
            <th>Amount</th>
            <th>Type</th>
            <th>Status</th>
            <th>Logo</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="accountList">
          <!-- content here from ajax -->
        </tbody>
      </table>

      <div class="d-flex justify-content-center align-items-center loader-table accountList">
        <img src="<?= base_url('assets/mazer/assets/images/svg-loaders/audio.svg'); ?>" alt="loader" class="img-fluid me-2" style="max-width: 200px;" />  
        <img src="<?= base_url('assets/mazer/assets/images/svg-loaders/audio.svg'); ?>" alt="loader" class="img-fluid" style="max-width: 200px;" />         
      </div>
    </div>
  </div>
</section>

<?php $this->load->view('keuangan/account/modal/addAccount'); ?>
<?php $this->load->view('keuangan/account/modal/editAccount'); ?>
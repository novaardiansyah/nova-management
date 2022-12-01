<section class="section tab-content typeCurrencyList d-none">
  <div class="card">
    <div class="card-header">
      <button type="button" class="btn btn-success btn-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#addData" onclick="return formModalReset()" id="addData-show">
        <i class="bi bi-plus-square"></i>
        <span class="d-none d-lg-inline">Add</span>
      </button>
    </div>
    <div class="card-body typeCurrencyList">
      <table class="table table-striped" id="typeCurrencyList">
        <thead>
          <tr>
            <th>No.</th>
            <th>Name</th>
            <th>Currency</th>
            <th>Amount</th>
            <th>Logo</th>
            <th>Type</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="typeCurrencyList">
          <!-- content here from ajax -->
        </tbody>
      </table>

      <div class="d-flex justify-content-center align-items-center loader-table typeCurrencyList">
        <img src="<?= base_url('assets/mazer/assets/images/svg-loaders/audio.svg'); ?>" alt="loader" class="img-fluid me-2" style="max-width: 200px;" />  
        <img src="<?= base_url('assets/mazer/assets/images/svg-loaders/audio.svg'); ?>" alt="loader" class="img-fluid" style="max-width: 200px;" />         
      </div>
    </div>
  </div>
</section>
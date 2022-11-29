<section class="tabs">
  <div class="card mb-1">
    <div class="card-body">
      <ul class="nav nav-pills">
        <li class="nav-item">
          <a href="#" class="nav-link active" onclick="return toggleTabs(event, 'accountList')">Account</a>
        </li>
        <!-- /.nav-item -->

        <li class="nav-item">
          <a href="#" class="nav-link" onclick="return toggleTabs(event, 'typeAccountList')">Type Account</a>
        </li>
        <!-- /.nav-item -->

        <li class="nav-item">
          <a href="#" class="nav-link" onclick="return toggleTabs(event, 'typeCurrency')">Type Currency</a>
        </li>
        <!-- /.nav-item -->
      </ul>
    </div>
  </div>
</section>

<section class="section accountList">
  <div class="card">
    <div class="card-header">
      <button type="button" class="btn btn-success btn-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#addData" onclick="return formModalReset()" id="addData-show">
        <i class="bi bi-plus-square"></i>
        <span class="d-none d-lg-inline">Add</span>
      </button>
    </div>
    <div class="card-body accountList">
      <table class="table table-striped" id="accountList">
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
<section class="section">
  <div class="card">
    <div class="card-header">
      <button type="button" class="btn btn-success btn-sm text-nowrap" data-bs-toggle="modal" data-bs-target="#addData" id="addData-show">
        <i class="bi bi-plus-square"></i>
        <span class="d-none d-lg-inline">Add</span>
      </button>
    </div>
    <div class="card-body">
      <table class="table table-striped" id="menuList">
        <thead>
          <tr>
            <th>No.</th>
            <th>Name</th>
            <th>Icon</th>
            <th>Link</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody class="menuList">
          <!-- content here from ajax -->
        </tbody>
      </table>
    </div>
  </div>
</section>

<?php $this->load->view('masterData/menu/modal/add'); ?>
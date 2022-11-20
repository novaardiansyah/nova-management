<?php 
  $menu = $dataMenu->status ? $dataMenu->data->menu : [];
?>
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
        <tbody>
          <?php foreach ($menu as $key => $value) : ?>
            <tr>
              <td><?= $key + 1; ?></td>
              <td><?= $value->name; ?></td>
              <td><?= $value->icon; ?></td>
              <td><?= $value->link; ?></td>
              <td>
                <?php if ((int) $value->isActive == 1) : ?>
                  <span class="badge bg-success">Active</span>
                <?php else : ?>
                  <span class="badge bg-danger">Non-Active</span>
                <?php endif; ?>
              </td>
              <td>
                <button type="button" class="btn btn-primary btn-sm text-nowrap">
                  <i class="bi bi-pencil-square"></i>
                  <span class="d-none d-xl-inline">Edit</span>
                </button>

                <button type="button" class="btn btn-info btn-sm text-nowrap">
                  <i class="bi bi-eye"></i>
                  <span class="d-none d-xl-inline">Detail</span>
                </button>

                <button type="button" class="btn btn-danger btn-sm text-nowrap">
                  <i class="bi bi-x-square"></i>
                  <span class="d-none d-xl-inline">Delete</span>
                </button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<?php $this->load->view('masterData/menu/modal/add'); ?>
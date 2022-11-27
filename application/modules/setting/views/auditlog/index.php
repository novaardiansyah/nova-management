<section class="section">
  <div class="card">
    <div class="card-body auditlogList">
      <div class="table-responsive">
        <table class="table table-striped" id="auditlogList">
          <thead class="text-nowrap">
            <tr>
              <th>No.</th>
              <th>Username</th>
              <th>User ID</th>
              <th>User Role</th>
              <th>IP Address</th>
              <th>Audit Type</th>
              <th>Comment</th>
              <th>Created At</th>
            </tr>
          </thead>
          <tbody class="auditlogList">
            <!-- content here from ajax -->
          </tbody>
        </table>
      </div>

      <div class="d-flex justify-content-center align-items-center" id="loader-table">
        <img src="<?= base_url('assets/mazer/assets/images/svg-loaders/audio.svg'); ?>" alt="loader" class="img-fluid me-2" style="max-width: 200px;" />  
        <img src="<?= base_url('assets/mazer/assets/images/svg-loaders/audio.svg'); ?>" alt="loader" class="img-fluid" style="max-width: 200px;" />         
      </div>
    </div>
  </div>
</section>
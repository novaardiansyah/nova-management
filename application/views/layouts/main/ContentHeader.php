<?php if (isset($bc)) : ?>
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h4 class="m-0"><?= $title ?></h4>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <?php foreach($bc as $key_1 => $row_1) : ?>
              <?php foreach($row_1 as $key_2 => $row_2) : ?>
                <?php if($row_2 && $row_2 != '') : ?>
                  <li class="breadcrumb-item">
                    <a href="<?= base_url($row_2) ?>"><?= $key_2 ?></a>
                  </li>
                <?php else : ?>
                  <li class="breadcrumb-item active"><?= $key_2 ?></li>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endforeach; ?>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
<?php else : ?>
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-8">
          <h4 class="m-0"><?= $title ?></h4>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
<?php endif; ?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index3.html" class="brand-link">
    <img src="<?= adminlte('dist/img/AdminLTELogo.png') ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light"><?= $this->config->item('APP_LONG_NAME') ?></span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <?php foreach ($menu as $key_1 => $row_1) : ?>
          <li class="nav-item">
            <a href="<?= base_url($row_1->url); ?>" class="nav-link">
              <i class="nav-icon fa fa-fw <?= $row_1->icon ?>"></i>
              <p>
                <?= $row_1->name; ?>
                <?php if (!empty($row_1->submenus)) : ?>
                  <i class="right fas fa-angle-left"></i>
                <?php endif; ?>
              </p>
            </a>

            <?php if (!empty($row_1->submenus)) : ?>
              <ul class="nav nav-treeview">
                <?php foreach ($row_1->submenus as $key_2 => $row_2) : ?>
                  <li class="nav-item">
                    <a href="<?= base_url($row_2->url) ?>" class="nav-link">
                      <i class="fa fa-fw <?= $row_2->icon ?>"></i>
                      <p><?= $row_2->name; ?></p>
                    </a>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php endif; ?>
          </li>
        <?php endforeach; ?>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
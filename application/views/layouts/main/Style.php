<!-- Google Font: Source Sans Pro -->
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
<!-- Font Awesome -->
<link rel="stylesheet" href="<?= adminlte('plugins/fontawesome-free/css/all.min.css') ?>" />
<!-- Ionicons -->
<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" />
<!-- iCheck -->
<link rel="stylesheet" href="<?= adminlte('plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>" />
<!-- Theme style -->
<link rel="stylesheet" href="<?= adminlte('dist/css/adminlte.min.css') ?>" />
<!-- overlayScrollbars -->
<link rel="stylesheet" href="<?= adminlte('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>" />
<!-- Daterange picker -->
<link rel="stylesheet" href="<?= adminlte('plugins/daterangepicker/daterangepicker.css') ?>" />
<!-- Datatables -->
<link rel="stylesheet" href="<?= adminlte('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>" />
<link rel="stylesheet" href="<?= adminlte('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>" />
<link rel="stylesheet" href="<?= adminlte('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>" />
<!-- Main -->
<link rel="stylesheet" href="<?= base_url('assets/css/main.css' . version()) ?>" />

<?php if (isset($styles)) : ?>
  <?php foreach ($styles as $row) : ?>
    <link rel="<?= $row['rel'] ?? 'stylesheet' ?>" href="<?= $row['path'] ?>" />
  <?php endforeach; ?>
<?php endif; ?>
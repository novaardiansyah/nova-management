<!-- jQuery -->
<script src="<?= adminlte('plugins/jquery/jquery.min.js') ?>"></script>
<!-- jQuery UI 1.11.4 -->
<script src="<?= adminlte('plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="<?= adminlte('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<!-- ChartJS -->
<script src="<?= adminlte('plugins/chart.js/Chart.min.js') ?>"></script>
<!-- daterangepicker -->
<script src="<?= adminlte('plugins/moment/moment.min.js') ?>"></script>
<script src="<?= adminlte('plugins/daterangepicker/daterangepicker.js') ?>"></script>
<!-- overlayScrollbars -->
<script src="<?= adminlte('plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') ?>"></script>
<!-- Datatables -->
<script src="<?= adminlte('plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= adminlte('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= adminlte('plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= adminlte('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<!-- SweetAlert -->
<script src="<?= assets('plugins/template-admin/plugins/sweetalert2/sweetalert2.all.min.js'); ?>"></script>

<!-- AdminLTE App -->
<script src="<?= adminlte('dist/js/adminlte.js') ?>"></script>
<!-- Core JS -->
<script src="<?= base_url('assets/js/core/utils.js' . version()) ?>"></script>

<?php if (isset($scripts)) : ?>
  <?php foreach ($scripts as $row) : ?>
    <script src="<?= $row['path'] ?>" type="<?= $row['type'] ?? 'text/javascript' ?>"></script>
  <?php endforeach; ?>
<?php endif; ?>
    <?php $this->load->view('layouts/main/Header') ?>
    <?php $this->load->view('layouts/main/Navbar') ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <?php $this->load->view('layouts/main/ContentHeader') ?>
      
      <!-- Main content -->
      <section class="content">
        <?php $this->load->view($view); ?>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    
    <?php $this->load->view('layouts/main/Sidebar') ?>
    <?php $this->load->view('layouts/main/Footer') ?>
    <?php $this->load->view('layouts/main/ControlSidebar') ?>
    
    </div>
    <!-- ./wrapper -->

    <script>
      const config = {
        base_url: '<?= base_url() ?>',
        csrf_token: '<?= $this->security->get_csrf_hash() ?>'
      }
    </script>

    <?php $this->load->view('layouts/main/Script') ?>
  </body>
</html>
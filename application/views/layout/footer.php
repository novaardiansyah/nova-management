    </div>
      <footer>
        <div class="footer clearfix mb-0 text-muted me-3">
          <div class="float-start">
            <p><?= getTimes('now', 'Y') ?> &copy; Nova Management</p>
          </div>
          <div class="float-end">
            <p>Crafted with <span class="text-danger"><i class="bi bi-heart"></i></span> by <a href="https://github.com/novaardiansyah#">Nova Ardiansyah</a></p>
          </div>
        </div>
      </footer>
    </div>

    <script>
      const startup = {
        baseurl: `<?= base_url(); ?>`,
        crlf_token: `<?= $this->security->get_csrf_hash(); ?>`,
        crlf_name: `<?= $this->security->get_csrf_token_name(); ?>`,
        colors: {
          info: '#33d3f3',
          danger: '#dc3545',
          success: '#198754',
          primary: '#435ebe'
        }
      }
    </script>

    <script src="<?= base_url('assets/mazer/assets/js/bootstrap.js') ?>"></script>
    <script src="<?= base_url('assets/mazer/assets/js/app.js'); ?>"></script>
    <script src="<?= base_url('assets/mazer/assets/extensions/toastify-js/src/toastify.js'); ?>"></script>
    <script src="<?= base_url('assets/mazer/assets/extensions/sweetalert2/sweetalert2.min.js'); ?>"></script>

    <?php if (isset($script)) : ?>
      <?php foreach ($script as $key => $value) : ?>
        <script src="<?= $value ?>"></script>
      <?php endforeach; ?>
    <?php endif; ?>
  </body>
</html>
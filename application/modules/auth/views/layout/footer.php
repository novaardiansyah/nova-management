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

    <script src="<?= base_url('assets/mazer/assets/extensions/toastify-js/src/toastify.js'); ?>"></script>
    <script src="<?= base_url('assets/mazer/assets/extensions/sweetalert2/sweetalert2.min.js'); ?>"></script>
    
    <?php if (isset($script)) : ?>
      <?php foreach ($script as $key => $value) : ?>
        <script src="<?= $value ?>"></script>
      <?php endforeach; ?>
    <?php endif; ?>
  </body>
</html>
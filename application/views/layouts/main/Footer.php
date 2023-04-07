<footer class="main-footer">
  <strong>Copyright &copy; <?= getTimestamp('now', '2022-Y') ?> <a href="<?= $this->config->item('APP_AUTHOR_WEB') ?>" target="_blank"><?= $this->config->item('APP_AUTHOR') ?></a>.</strong>
  All rights reserved.
  <div class="float-right d-none d-sm-inline-block">
    <b>Version</b> <?= $this->config->item('APP_VERSION') ?>
  </div>
</footer>
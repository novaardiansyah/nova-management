<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>
    <?= $pageTitle ?? $this->config->item('APP_SHORT_NAME') ?>
  </title>

  <?php $this->load->view('layouts/main/Favicon') ?>
  <?php $this->load->view('layouts/main/Style') ?>
</head>
  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
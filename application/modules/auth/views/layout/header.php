<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authorization - Nova Management</title>
    <link rel="stylesheet" href="<?= base_url('assets/mazer/assets/css/main/app.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/mazer/assets/css/pages/auth.css'); ?>">
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico'); ?>" type="image/png" />

    <link rel="stylesheet" href="<?= base_url('assets/mazer/assets/css/shared/iconly.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/mazer/assets/extensions/toastify-js/src/toastify.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/mazer/assets/extensions/sweetalert2/sweetalert2.min.css'); ?>">
    
    <?php if (isset($style)) : ?>
      <?php foreach ($style as $key => $value) : ?>
        <link rel="stylesheet" href="<?= $value ?>" />
      <?php endforeach; ?>
    <?php endif; ?>
  </head>
  <body>
    <div id="auth">
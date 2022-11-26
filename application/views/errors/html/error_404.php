<?php
defined('BASEPATH') OR exit('No direct script access allowed');

	$ci = get_instance();
	if (!isset($ci))
	{
		$ci = new CI_Controller();
	}

	$ci->load->helper('url');
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Nova Management</title>
    <link rel="stylesheet" href="<?= base_url('assets/mazer/assets/css/main/app.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/mazer/assets/css/pages/error.css'); ?>">
    <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico'); ?>" type="image/png">
  </head>
  <body>
    <div id="error">
      
      <div class="error-page container">
        <div class="col-md-8 col-12 offset-md-2">
          <div class="text-center">
            <img class="img-error" src="<?= base_url('assets/mazer/assets/images/samples/error-404.svg'); ?>" alt="Not Found">
            <h1 class="error-title">NOT FOUND</h1>
            <p class='fs-5 text-gray-600'>The page you are looking not found.</p>
            <a href="<?= base_url(); ?>" class="btn btn-lg btn-outline-primary mt-3">Go Home</a>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
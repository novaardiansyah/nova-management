<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>403 - Nova Management</title>
  <link rel="stylesheet" href="<?= base_url('assets/mazer/assets/css/main/app.css') ?>" />
  <link rel="stylesheet" href="<?= base_url('assets/mazer/assets/css/pages/error.css') ?>" />
  <link rel="shortcut icon" href="<?= base_url('assets/images/favicon.ico') ?>" type="image/x-icon" />
</head>

<body>
  <div id="error">
    <div class="error-page container">
      <div class="col-md-8 col-12 offset-md-2">
        <div class="text-center">
          <img class="img-error" src="<?= base_url('assets/mazer/assets/images/samples/error-403.svg'); ?>" alt="Not Found" />
          <h1 class="error-title">Forbidden</h1>
          <p class="fs-5 text-gray-600">
            You do not have access to view this page, maybe you are not logged in or you do not have access rights to view this page.
          </p>
          <a href="<?= base_url(); ?>" class="btn btn-lg btn-outline-primary mt-3">Go Home</a>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
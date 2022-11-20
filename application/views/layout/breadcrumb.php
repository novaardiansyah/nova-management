<div id="main">
  <header class="mb-3">
    <a href="#" class="burger-btn d-block d-xl-none">
      <i class="bi bi-justify fs-3"></i>
    </a>
  </header>
  
  <div class="page-heading">
    <div class="page-title">
      <div class="row">
        <div class="col-12 col-md-6 order-md-1 order-last">
          <h3><?= $title; ?></h3>
          <p class="text-subtitle text-muted"><?= $subtitle; ?></p>
        </div>
        <div class="col-12 col-md-6 order-md-2 order-first">
          <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
            <ol class="breadcrumb">
              <?php foreach ($breadcrumb as $key => $value) : ?>
              <?php if ((int) count($breadcrumb) == $key + 1) : ?>
              <li class="breadcrumb-item active" aria-current="page"><?= $value['title']; ?></li>
              <?php else : ?>
              <li class="breadcrumb-item"><a href="<?= $value['link']; ?>"><?= $value['title']; ?></a></li>
              <?php endif; ?>
              <?php endforeach; ?>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
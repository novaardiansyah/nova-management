<section class="tabs">
  <div class="card mb-1">
    <div class="card-body">
      <ul class="nav nav-pills">
        <li class="nav-item">
          <a href="#" class="nav-link active" onclick="return toggleTabs(event, 'accountList')">Account</a>
        </li>
        <!-- /.nav-item -->

        <li class="nav-item">
          <a href="#" class="nav-link" onclick="return toggleTabs(event, 'typeAccountList')">Type Account</a>
        </li>
        <!-- /.nav-item -->

        <li class="nav-item">
          <a href="#" class="nav-link" onclick="return toggleTabs(event, 'typeCurrencyList')">Type Currency</a>
        </li>
        <!-- /.nav-item -->
      </ul>
    </div>
  </div>
</section>

<?php $this->load->view('keuangan/account/components/accountList'); ?>
<?php $this->load->view('keuangan/account/components/typeAccountList'); ?>
<?php $this->load->view('keuangan/account/components/typeCurrencyList'); ?>
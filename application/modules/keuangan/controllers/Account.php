<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends MX_Controller 
{
  public $_modelPath;

  public function __construct()
  {
    parent::__construct();
    $this->_modelPath = 'M_Account';
  }

  public function index()
  {
    $this->load->helper('auth_helper');
    isLogin();

    $data = [
      'title'    => 'Daftar Akun',
      'subtitle' => 'Keuangan - Daftar Akun',
      'breadcrumb' => [
        ['title' => 'Keuangan', 'link' => base_url('keuangan/account')],
        ['title' => 'Daftar Akun', 'link' => base_url('keuangan/account')]
      ],
      'currency'    => requestModel($this->_modelPath, 'getCurrency', []),
      'typeAccount' => requestModel($this->_modelPath, 'getTypeAccount', []),

      'style' => [
        base_url('assets/mazer/assets/extensions/simple-datatables/style.css'),
        base_url('assets/css/main.css')
      ],
      'script' => [
        base_url('assets/mazer/assets/extensions/simple-datatables/umd/simple-datatables.js'),
        base_url('assets/js/main.js?v=' . getTimes('now', 'YmdH')),
        base_url('assets/js/keuangan/index.js?v=' . getTimes('now', 'YmdH')),
        base_url('assets/js/keuangan/account.js?v=' . getTimes('now', 'YmdH')),
        base_url('assets/js/keuangan/typeAccount.js?v=' . getTimes('now', 'YmdH')),
        base_url('assets/js/keuangan/typeCurrency.js?v=' . getTimes('now', 'YmdH'))
      ]
    ];

    backend_layout('account/index', $data);
  }

  public function accountList()
  {
    $result = requestModel($this->_modelPath, 'accountList', []);
    echo json_encode($result);
  }

  public function storeAccount()
  {
    $send = [
      'name'       => trim(isset($_POST['name']) ? $_POST['name'] : ''),
      'idCurrency' => trim(isset($_POST['idCurrency']) ? $_POST['idCurrency'] : ''),
      'idType'     => trim(isset($_POST['idType']) ? $_POST['idType'] : ''),
      'amount'     => trim(isset($_POST['amount']) ? $_POST['amount'] : ''),
      'isActive'   => trim(isset($_POST['isActive']) ? $_POST['isActive'] : '')
    ];

    $result = requestModel($this->_modelPath, 'storeAccount', $send);
    echo json_encode($result); 
  }
}
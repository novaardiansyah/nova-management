<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends MX_Controller 
{
  public $_modelPath;

  public function __construct()
  {
    parent::__construct();
    $this->_modelPath = 'keuangan/M_Account';
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
      'style' => [
        base_url('assets/mazer/assets/extensions/simple-datatables/style.css'),
        base_url('assets/css/main.css')
      ],
      'script' => [
        base_url('assets/mazer/assets/extensions/simple-datatables/umd/simple-datatables.js'),
        base_url('assets/js/main.js?v=1'),
        base_url('assets/js/keuangan/account.js')
      ]
    ];

    backend_layout('account/index', $data);
  }

  public function accountList()
  {
    $result = requestModel($this->_modelPath, 'accountList', []);
    echo json_encode($result);
  }
}
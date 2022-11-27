<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Auditlog extends MX_Controller 
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('M_Auditlog', 'auditlog');
  }

  public function index()
  {
    $this->load->helper('auth_helper');
    isLogin();

    $data = [
      'title'    => 'Auditlog',
      'subtitle' => 'Management Auditlog',
      'breadcrumb' => [
        ['title' => 'Setting', 'link' => base_url('setting/auditlog')],
        ['title' => 'Auditlog', 'link' => base_url('setting/auditlog')]
      ],
      'style' => [
        base_url('assets/mazer/assets/extensions/simple-datatables/style.css'),
        base_url('assets/css/main.css')
      ],
      'script' => [
        base_url('assets/mazer/assets/extensions/simple-datatables/umd/simple-datatables.js'),
        base_url('assets/js/main.js'),
        base_url('assets/js/setting/auditlog.js')
      ]
    ];

    backend_layout('auditlog/index', $data);
  }

  public function auditlogList()
  {
    $csrf_renewed = $this->security->get_csrf_hash();
    $result       = $this->auditlog->auditlogList(['csrf_renewed' => $csrf_renewed]);
    echo json_encode($result);
  }
}
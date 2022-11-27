<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class RoleAccess extends MX_Controller 
{
  public $_modelPath;

  public function __construct()
  {
    parent::__construct();
    $this->_modelPath = 'setting/M_RoleAccess';
  }

  public function index()
  {
    $this->load->helper('auth_helper');
    isLogin();

    $data = [
      'title'    => 'Role Access',
      'subtitle' => 'Menu - Role Access',
      'breadcrumb' => [
        ['title' => 'Setting', 'link' => base_url('setting/auditlog')],
        ['title' => 'Role Access', 'link' => base_url('setting/roleAccess')]
      ],
      'style' => [
        base_url('assets/mazer/assets/extensions/simple-datatables/style.css'),
        base_url('assets/css/main.css')
      ],
      'script' => [
        base_url('assets/mazer/assets/extensions/simple-datatables/umd/simple-datatables.js'),
        base_url('assets/js/main.js'),
        base_url('assets/js/setting/roleAccess.js')
      ]
    ];

    backend_layout('roleAccess/index', $data);
  }
  
  public function listRoleAccount()
  {
    $result = requestModel($this->_modelPath, 'listRoleAccount');
    echo json_encode($result);
  }
}
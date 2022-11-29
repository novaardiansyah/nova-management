<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Submenu extends MX_Controller 
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('M_Submenu', 'submenu');
  }

  public function index()
  {
    $this->load->helper('auth_helper');
    isLogin();

    $data = [
      'title'    => 'Submenu',
      'subtitle' => 'Management Submenu',
      'breadcrumb' => [
        ['title' => 'Master Data', 'link' => base_url('masterData')],
        ['title' => 'Submenu', 'link' => base_url('masterData/submenu')]
      ],
      'menuData' => $this->submenu->dropdownMenu(),
      'style' => [
        base_url('assets/mazer/assets/extensions/simple-datatables/style.css'),
        base_url('assets/css/main.css')
      ],
      'script' => [
        base_url('assets/mazer/assets/extensions/simple-datatables/umd/simple-datatables.js'),
        base_url('assets/js/main.js'),
        base_url('assets/js/masterData/submenu.js?v=1')
      ]
    ];
    
    backend_layout('submenu/index', $data);
  }

  public function submenuList()
  {
    $csrf_renewed = $this->security->get_csrf_hash();
    $result = $this->submenu->getSubmenu(['csrf_renewed' => $csrf_renewed]);
    echo json_encode($result);
  }

  public function addData()
  { 
    $csrf_renewed = $this->security->get_csrf_hash();
    $validate     = $this->_r_addData();

    if ($validate->run() == false)
    {
      echo json_encode(['status' => false, 'message' => 'Validation is invalid.', 'data' => ['csrf_renewed' => $csrf_renewed, 'errors' => $validate->error_array()]]); exit;
    }

    $send = [
      'csrf_renewed' => $csrf_renewed,
      'name'         => trim(isset($_POST['name']) ? $_POST['name'] : ''),
      '_idMenu'      => trim(isset($_POST['idMenu']) ? $_POST['idMenu'] : ''),
      'link'         => trim(isset($_POST['link']) ? '/' . $_POST['link'] : ''),
      'sortOrder'    => trim(isset($_POST['sortOrder']) ? $_POST['sortOrder'] : ''),
      'isActive'     => trim(isset($_POST['isActive']) ? $_POST['isActive'] : '')
    ];

    $result = $this->submenu->addData($send);
    echo json_encode($result);
  }

  private function _r_addData()
  {
    $rules = [
      ['field' => 'idMenu', 'label' => 'Menu', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'name', 'label' => 'Submenu', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'link', 'label' => 'Link', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'sortOrder', 'label' => 'Sort Order', 'rules' => 'required|trim|numeric|min_length[1]|max_length[5]|greater_than[-1]'],
      ['field' => 'isActive', 'label' => 'Status', 'rules' => 'required|trim|numeric|max_length[1]']
    ];

    return $this->form_validation->set_rules($rules);
  }

  public function deleteData()
  {
    $csrf_renewed = $this->security->get_csrf_hash();

    $send = [
      'csrf_renewed' => $csrf_renewed,
      '_id'          => trim(isset($_POST['_id']) ? $_POST['_id'] : ''),
    ];

    $result = $this->submenu->deleteData($send);
    echo json_encode($result);
  }

  public function editData()
  {
    $csrf_renewed = $this->security->get_csrf_hash();

    $send = [
      'csrf_renewed' => $csrf_renewed,
      '_id'          => trim(isset($_POST['_id']) ? $_POST['_id'] : ''),
    ];

    $result = $this->submenu->editData($send);
    echo json_encode($result);
  }

  public function updateData()
  {
    $csrf_renewed = $this->security->get_csrf_hash();
    $validate     = $this->_r_updateData();

    if ($validate->run() == false)
    {
      echo json_encode(['status' => false, 'message' => 'Validation is invalid.', 'data' => ['csrf_renewed' => $csrf_renewed, 'errors' => $validate->error_array()]]); exit;
    }

    $send = [
      'csrf_renewed' => $csrf_renewed,
      '_id'          => trim(isset($_POST['id']) ? $_POST['id'] : ''),
      '_idMenu'      => trim(isset($_POST['idMenu']) ? $_POST['idMenu'] : ''),
      'name'         => trim(isset($_POST['name']) ? $_POST['name'] : ''),
      'link'         => trim(isset($_POST['link']) ? '/' . $_POST['link'] : ''),
      'sortOrder'    => trim(isset($_POST['sortOrder']) ? $_POST['sortOrder'] : ''),
      'isActive'     => trim(isset($_POST['isActive']) ? $_POST['isActive'] : '')
    ];

    $result = $this->submenu->updateData($send);
    echo json_encode($result);
  }

  private function _r_updateData()
  {
    $rules = [
      ['field' => 'idMenu', 'label' => 'Menu', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'name', 'label' => 'Submenu', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'link', 'label' => 'Link', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'sortOrder', 'label' => 'Sort Order', 'rules' => 'required|trim|numeric|min_length[1]|max_length[5]|greater_than[-1]'],
      ['field' => 'isActive', 'label' => 'Status', 'rules' => 'required|trim|numeric|max_length[1]']
    ];

    return $this->form_validation->set_rules($rules);
  }

  public function getMenu()
  {
    $key = trim(isset($_GET['key']) ? $_GET['key'] : '');
    $validateKey = validateKey($key);
    if (!$validateKey->status) {
      echo json_encode($validateKey); exit;
    }

    $result = $this->submenu->getMenu();
    echo json_encode($result);
  }
}
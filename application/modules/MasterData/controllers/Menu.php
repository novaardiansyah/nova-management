<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends MX_Controller 
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('M_Menu', 'menu');
  }

  public function index()
  {
    $this->load->helper('auth_helper');
    isLogin();

    $data = [
      'title'    => 'Menu',
      'subtitle' => 'Management Menu',
      'breadcrumb' => [
        ['title' => 'Master Data', 'link' => base_url('masterData')],
        ['title' => 'Menu', 'link' => base_url('masterData/menu')]
      ],
      'style' => [
        base_url('assets/mazer/assets/extensions/simple-datatables/style.css'),
        base_url('assets/css/main.css')
      ],
      'script' => [
        base_url('assets/mazer/assets/extensions/simple-datatables/umd/simple-datatables.js'),
        base_url('assets/js/main.js'),
        base_url('assets/js/masterData/menu.js')
      ]
    ];

    backend_layout('menu/index', $data);
  }

  public function menuList()
  {
    $csrf_renewed = $this->security->get_csrf_hash();
    $result = $this->menu->getMenu(['csrf_renewed' => $csrf_renewed]);
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
      'icon'         => trim(isset($_POST['icon']) ? 'bi bi-' . $_POST['icon'] : ''),
      'link'         => trim(isset($_POST['link']) ? '/' . $_POST['link'] : ''),
      'sortOrder'    => trim(isset($_POST['sortOrder']) ? $_POST['sortOrder'] : ''),
      'isActive'     => trim(isset($_POST['isActive']) ? $_POST['isActive'] : '')
    ];

    $result = $this->menu->addData($send);
    echo json_encode($result);
  }

  private function _r_addData()
  {
    $rules = [
      ['field' => 'name', 'label' => 'Name', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'icon', 'label' => 'Icon', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'link', 'label' => 'Link', 'rules' => 'trim|max_length[120]'],
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

    $result = $this->menu->deleteData($send);
    echo json_encode($result);
  }

  public function editData()
  {
    $csrf_renewed = $this->security->get_csrf_hash();

    $send = [
      'csrf_renewed' => $csrf_renewed,
      '_id'          => trim(isset($_POST['_id']) ? $_POST['_id'] : ''),
    ];

    $result = $this->menu->editData($send);
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
      'name'         => trim(isset($_POST['name']) ? $_POST['name'] : ''),
      'icon'         => trim(isset($_POST['icon']) ? 'bi bi-' . $_POST['icon'] : ''),
      'link'         => trim(isset($_POST['link']) ? '/' . $_POST['link'] : ''),
      'sortOrder'    => trim(isset($_POST['sortOrder']) ? $_POST['sortOrder'] : ''),
      'isActive'     => trim(isset($_POST['isActive']) ? $_POST['isActive'] : '')
    ];

    $result = $this->menu->updateData($send);
    echo json_encode($result);
  }

  private function _r_updateData()
  {
    $rules = [
      ['field' => 'name', 'label' => 'Name', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'icon', 'label' => 'Icon', 'rules' => 'required|trim|max_length[120]'],
      ['field' => 'link', 'label' => 'Link', 'rules' => 'trim|max_length[120]'],
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

    $result = $this->menu->getMenu();
    echo json_encode($result);
  }
}
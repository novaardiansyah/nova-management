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
    $data = [
      'title' => 'Menu',
      'subtitle' => 'Management Menu',
      'breadcrumb' => [
        ['title' => 'Master Data', 'link' => base_url('masterData')],
        ['title' => 'Menu', 'link' => base_url('masterData/menu')]
      ],
      'dataMenu' => $this->menu->getMenu(),

      'script' => [
        base_url('assets/js/masterData/menu.js')
      ]
    ];

    backend_layout('menu/index', $data);
  }

  public function addData()
  {
    $send = [
      'csrf_renewed' => $this->security->get_csrf_hash(),
      'name'         => trim(isset($_POST['name']) ? $_POST['name'] : ''),
      'icon'         => trim(isset($_POST['icon']) ? $_POST['icon'] : ''),
      'link'         => trim(isset($_POST['link']) ? $_POST['link'] : ''),
      'isActive'     => trim(isset($_POST['isActive']) ? $_POST['isActive'] : '')
    ];

    $result = $this->menu->addData($send);
    echo json_encode($result);
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
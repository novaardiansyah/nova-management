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
      'menu' => $this->menu->getMenu()
    ];

    echo json_encode(getKey());
  }

  public function getMenu()
  {
    $key = trim(isset($_GET['key']) ? $_GET['key'] : '');
    $validateKey = validateKey($key);
    if (!$validateKey['status']) {
      echo json_encode($validateKey); exit;
    }

    $result = $this->menu->getMenu();
    echo json_encode($result);
  }
}
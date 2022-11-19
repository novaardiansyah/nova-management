<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Main extends CI_Model 
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getMenu()
  {
    $result = $this->db->query("SELECT a.id, a.name, a.icon, a.link, a.isActive FROM menu AS a WHERE a.isActive = 1")->result();

    if (empty($result)) return arrayToObject(['status' => false, 'message' => 'Data tidak ditemukan.', 'data' => ['error' => 'I2W3']]);

    $menu = [];

    foreach ($result as $key => $value) 
    {
      $result[$key]->isSingle = 1;

      $submenu = $this->db->query("SELECT a.id, a.idMenu, a.name, a.link, a.isActive FROM submenu AS a WHERE a.isActive = 1 AND a.idMenu = '$value->id'")->result();

      $temp = [
        'menu' => $result[$key]
      ];

      if (!empty($submenu)) 
      {
        $result[$key]->isSingle = 0;
        $temp['submenu'] = $submenu;
      }

      array_push($menu, $temp);
    }

    $response = arrayToObject(['status' => true, 'message' => 'Data berhasil ditemukan.', 'data' => []]);
    $response->data = $menu;
    
    return $response;
  }
}
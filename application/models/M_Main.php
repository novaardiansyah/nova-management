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
    $result = $this->db->query("SELECT a.id, a.name, a.icon, a.link, a.sortOrder, a.isActive FROM menu AS a WHERE a.isActive = 1 AND a.isDeleted = 0 ORDER BY a.sortOrder ASC")->result();

    if (empty($result)) return ['status' => false, 'message' => 'Data tidak ditemukan.', 'data' => ['error' => 'SHW8Z']];

    $menu = [];

    foreach ($result as $key => $value) 
    {
      $result[$key]->isSingle = 1;

      $submenu = $this->db->query("SELECT a.id, a.idMenu, a.name, a.link, a.sortOrder, a.isActive FROM submenu AS a WHERE a.isActive = 1 AND a.idMenu = '$value->id' ORDER BY a.sortOrder ASC")->result();

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

    return ['status' => true, 'message' => 'Data berhasil ditemukan.', 'data' => $menu];
  }

  public function getMainLogo($data = [])
  {
    $csrf_renewed = trim(isset($data['csrf_renewed']) ? $data['csrf_renewed'] : '');

    $result = $this->db->query("SELECT a.id, a.name, a.path, a.isActive, a.isDeleted FROM assets AS a WHERE a.id = 1 AND a.isActive = 1 AND a.isDeleted = 0")->row();
    if (empty($result)) return ['status' => false, 'message' => 'Data tidak ditemukan.', 'data' => ['error' => 'GO6RM', 'csrf_renewed' => $csrf_renewed, 'query' => $this->db->last_query()]];

    $response = [
      'logo' => $result,
      'csrf_renewed' => $csrf_renewed
    ];

    return ['status' => true, 'message' => 'Data berhasil ditemukan.', 'data' => $response];
  }
}
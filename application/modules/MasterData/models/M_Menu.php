<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Menu extends CI_Model 
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getMenu()
  {
    $result = $this->db->query("SELECT a.id, a.name, a.icon, a.link, a.isActive FROM menu AS a")->result();
    
    if (empty($result)) return arrayToObject(['status' => false, 'message' => 'Data tidak ditemukan.', 'data' => ['error' => 'I2W3']]);

    $response = arrayToObject(['status' => true, 'message' => 'Data berhasil ditemukan.', 'data' => [
      'menu' => []
    ]]);
    $response->data->menu = $result;
    
    return $response;
  }

  public function addData($data = [])
  {
    $csrf_renewed = trim(isset($data['csrf_renewed']) ? $data['csrf_renewed'] : '');

    $send = [
      'name'       => trim(isset($data['name']) ? $data['name'] : ''),
      'icon'       => trim(isset($data['icon']) ? $data['icon'] : ''),
      'link'       => trim(isset($data['link']) ? $data['link'] : ''),
      'isActive'   => trim(isset($data['isActive']) ? $data['isActive'] : 0),
      'created_by' => 1
    ];

    $this->db->insert('menu', $send);
    $send['csrf_renewed'] = $csrf_renewed;

    return ['status' => true, 'message' => 'Berhasil menambah data baru.', 'data' => $send];
  }
}
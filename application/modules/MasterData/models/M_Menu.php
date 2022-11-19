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
    $result = $this->db->query("SELECT a.* FROM menu AS a")->result();
    if (!empty($result)) return arrayToObject(['status' => false, 'message' => 'Data tidak ditemukan.', 'data' => ['error' => 'I2W3']]);

    $response = arrayToObject(['status' => true, 'message' => 'Data berhasil ditemukan.', 'data' => [
      'menu' => []
    ]]);
    $response->data->menu = $result;
    
    return $response;
  }
}
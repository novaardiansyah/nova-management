<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Menu extends CI_Model 
{
  public function __construct()
  {
    parent::__construct();
  }

  public function getMenu($data = [])
  {
    $csrf_renewed = trim(isset($data['csrf_renewed']) ? $data['csrf_renewed'] : '');

    $result = $this->db->query("SELECT a.id, a.name, a.icon, a.link, a.isActive FROM menu AS a WHERE a.isDeleted = 0 ORDER BY a.created_at DESC")->result();
    
    if (empty($result)) return arrayToObject(['status' => false, 'message' => 'Data tidak ditemukan.', 'data' => ['error' => 'I2W3']]);

    foreach ($result as $key => $value) 
    {
      $result[$key]->id = custom_encode($value->id);
    }

    $response = arrayToObject(['status' => true, 'message' => 'Data berhasil ditemukan.', 'data' => [
      'menu' => [],
      'csrf_renewed' => ''
    ]]);
    $response->data->menu = $result;
    $response->data->csrf_renewed = $csrf_renewed;
    
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

  public function deleteData($data = [])
  {
    $csrf_renewed = trim(isset($data['csrf_renewed']) ? $data['csrf_renewed'] : '');
    $_id = trim(isset($data['_id']) ? $data['_id'] : '');
    $id  = $_id ? custom_decode($_id) : '';

    $result = $this->db->query("SELECT a.id, a.name, a.icon, a.link, a.isActive FROM menu AS a WHERE a.id = '$id'")->result();
    
    if (empty($result)) return ['status' => false, 'message' => 'Data tidak ditemukan.', 'data' => ['error' => 'L9C3O']];

    foreach ($result as $key => $value) 
    {
      $result[$key]->id = custom_encode($value->id);
    }

    $send = [
      'id'         => $id,
      'isDeleted'  => 1,
      'isActive'   => 0,
      'deleted_by' => 1,
      'deleted_at' => getTimes('now')
    ];

    $this->db->update('menu', $send, ['id' => $id]);
    
    $result['csrf_renewed'] = $csrf_renewed;

    return ['status' => true, 'message' => 'Berhasil menghapus data.', 'data' => $result];
  }
}